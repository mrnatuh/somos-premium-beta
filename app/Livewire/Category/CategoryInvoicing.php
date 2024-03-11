<?php

namespace App\Livewire\Category;

use App\Models\Invoicing;
use App\Models\Option;
use App\Models\Preview;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Str;

class CategoryInvoicing extends Component
{
	public $deleteItem = [];
	public $deleteCompanyColumn = [];
	public $lastOfMonth = 0;
	public $companies = [];
	public $edit = true;
	public $realizadas = "0";
	public $month_ref = null;
	public $realizadas_genial = null;

	public function updateQty($companyIndex, $rowIndex, $qtyIndex, $value)
	{
		if (isset($this->companies[$companyIndex])) {
			if (isset($this->companies[$companyIndex]['rows'])) {
				$this->companies[$companyIndex]['rows'][$rowIndex][$qtyIndex]['value'] = (int) $value ?? 0;

				$this->save();
			}
		}
	}

	public function addColumnPrice($companyIndex, $id_column)
	{
		if (isset($this->companies[$companyIndex])) {
			// atualiza o nome da coluna
			$colspan = sizeof($this->companies[$companyIndex]['labels']);

			$prices_options = $this->companies[$companyIndex]['prices_options'];
			$price = null;
			foreach ($prices_options as $price_option) {
				if ($price_option['id'] == $id_column) {
					$price = $price_option;
				}
			}

			if (!$price) {
				return;
			}

			for ($r = 0; $r < sizeof($this->companies[$companyIndex]['rows']); $r++) {
				$this->companies[$companyIndex]['rows'][$r][$colspan]['name'] = $price['value'];
				$this->companies[$companyIndex]['rows'][$r][$colspan]['id'] = $price['id'];
			}

			// cadastra novo label de preco
			array_push($this->companies[$companyIndex]['labels'], $price['value']);

			// atualiza o valor
			array_push(
				$this->companies[$companyIndex]['prices'],
				$this->companies[$companyIndex]['prices_vlr'][Str::slug($price['value'])]
			);

			// adiciona o preco selecionado
			array_push($this->companies[$companyIndex]['prices_selected'], $price['value']);

			// atualiza a lista de quantidade
			for ($r = 0; $r < sizeof($this->companies[$companyIndex]['rows']); $r++) {
				array_push($this->companies[$companyIndex]['rows'][$r], [
					'value' => 0,
					'name' => 'OUTRO',
				]);
			}

			// pega a quantidade de colunas
			$this->companies[$companyIndex]['colspan'] = sizeof($this->companies[$companyIndex]['rows'][0]);

			$this->save();

			$this->redirect('/categoria?filter=faturamento', navigate: true);
		}
	}

	#[On('search-add-client')]
	public function addClient($client_id)
	{
		$client = DB::connection('mysql_dump')
			->table('CLIENTES')
			->where('A1_COD', $client_id)
			->first();

		if (!$client) {
			return;
		}

		$exists = false;

		foreach ($this->companies as $company) {
			if ($company['id'] === $client->A1_CGC) {
				$exists = true;
			}
		}

		$precos_vlr = [];
		$precos_options = [];

		if (isset($client->A1_TABELA)) {
			$prices = DB::connection('mysql_dump')
				->table('TABELAPRECO')
				->where('DA0_CODTAB', $client->A1_TABELA)
				->get();

			foreach ($prices as $row) {
				$preco_option = Str::slug(trim($row->B1_DESC));

				array_push($precos_options, [
					"value" => $preco_option,
					"label" => trim($row->B1_DESC),
					"id" => trim($row->DA1_CODPRO),
				]);

				$precos_vlr[$preco_option] = [
					'value' => $row->DA1_PRCVEN,
					'edit' => true,
					'name' => $preco_option,
					"id" => trim($row->DA1_CODPRO),
				];
			}
		}

		if ($exists) {
			return;
		}

		$add_client = [
			"id" => trim($client->A1_CGC),

			"title" => trim($client->A1_NOME),

			"colspan" => 1,
			"rowspan" => $this->lastOfMonth,
			"last_of_month" => $this->lastOfMonth,

			"prices_selected" => [],
			"prices_vlr" => $precos_vlr,
			"prices_options" => $precos_options,

			"labels" => [],
			"prices" => [],

			"rows" => []
		];

		for ($i = 0; $i < $add_client['rowspan']; $i++) {
			$cols = [];

			for ($c = 0; $c < $add_client['colspan']; $c++) {
				array_push($cols, [
					'value' => 0,
					'name' => 'OUTRO'
				]);
			}

			array_push($add_client['rows'], $cols);
		}

		array_push($this->companies, $add_client);

		$this->save();
	}

	public function getTotal()
	{
		$total = 0;

		foreach ($this->companies as $company) {
			$prices = sizeof($company['prices']) ? $company['prices'] : [];
			$rows = $company['rows'];
			$colspan = sizeof($prices);
			$rowspan = $company['rowspan'];

			if (sizeof($prices)) {
				for ($c = 0; $c < $colspan; $c++) {
					$price = $prices[$c]['value'] ?? false;
					if ($price) {
						for ($r = 0; $r < $rowspan; $r++) {
							$qty = $rows[$r][$c]['value'];
							$total += $price * $qty;
						}
					}
				}
			}
		}

		return $total;
	}

	public function deleteRowItem($id)
	{
		$this->deleteItem[$id] = true;
	}

	public function cancelDeleteItem($id)
	{
		$this->deleteItem[$id] = false;
	}

	public function confirmDeleteItem($id)
	{
		$rowIndex = -1;

		foreach ($this->companies as $key => $company) {
			if ($company['id'] === $id) {
				$rowIndex = $key;
			}
		}

		if ($rowIndex > -1) {
			unset($this->companies[$rowIndex]);
		}

		$this->save();
	}

	public function deleteColumnItem($companyIndex, $columnIndex)
	{
		if (!isset($this->deleteCompanyColumn[$companyIndex])) {
			$this->deleteCompanyColumn[$companyIndex] = [];
		}

		$this->deleteCompanyColumn[$companyIndex][$columnIndex] = true;
	}

	public function cancelDeleteColumnItem($companyIndex, $columnIndex)
	{
		if (!isset($this->deleteCompanyColumn[$companyIndex])) {
			$this->deleteCompanyColumn[$companyIndex] = [];
		}

		$this->deleteCompanyColumn[$companyIndex][$columnIndex] = false;
	}

	public function confirmDeleteColumnItem($companyIndex, $labelIndex)
	{
		if (isset($this->companies[$companyIndex])) {
			$value = $this->companies[$companyIndex]['labels'][$labelIndex];

			$tmp_prices_selected = [];
			foreach ($this->companies[$companyIndex]['prices_selected'] as $label) {
				if ($label != $value) {
					array_push($tmp_prices_selected, $label);
				}
			}
			$this->companies[$companyIndex]['prices_selected'] = $tmp_prices_selected;

			// deleta o label
			$tmp_labels = [];
			foreach ($this->companies[$companyIndex]['labels'] as $label) {
				if ($label != $value) {
					array_push($tmp_labels, $label);
				}
			}
			$this->companies[$companyIndex]['labels'] = $tmp_labels;

			// deleta o preço
			$tmp_prices = [];
			foreach ($this->companies[$companyIndex]['prices'] as $price) {
				if ($price['name'] != $value) {
					array_push($tmp_prices, $price);
				}
			}
			$this->companies[$companyIndex]['prices'] = $tmp_prices;

			// update rows
			for ($r = 0; $r < sizeof($this->companies[$companyIndex]['rows']); $r++) {
				$tmp_rows = [];
				foreach ($this->companies[$companyIndex]['rows'][$r] as $item) {
					if ($item['name'] != $value) {
						array_push($tmp_rows, $item);
					}
				}
				$this->companies[$companyIndex]['rows'][$r] = $tmp_rows;
			}

			// pega a quantidade de colunas
			$this->companies[$companyIndex]['colspan'] = sizeof($this->companies[$companyIndex]['rows'][0]);

			$this->deleteCompanyColumn[$companyIndex][$labelIndex] = false;

			$this->save();
		}
	}

	public function save()
	{
		if (!$this->edit) return;

		$cc = session('preview')['cc'];
		$weekref = session('preview')['week_ref'];

		// acha o preview
		$preview = Preview::where([
			['cc', '=', $cc],
			['week_ref', '=', $weekref]
		])->first();

		// calcula o total
		$total = $this->getTotal();
		$preview->invoicing = $total;

		$preview->save();

		// serializa o conteúdo
		$content = serialize($this->companies);

		// cria ou faz update da invoicing para aquela prévia
		Option::updateOrCreate(
			[
				'cc' => $cc,
				'week_ref' => $weekref,
				'option_name' => 'faturamento',
			],
			[
				'option_value' => $content,
				'total' => $total,
			]
		);

		session()->flash('message', [
			'type' => 'success',
			'message' => 'Salvo com sucesso.',
		]);

		$this->dispatch('update-bar-total', [
			'cc' => $cc,
			'weekref' => $weekref
		]);
	}

	public function render()
	{
		$this->lastOfMonth = (int) Carbon::now()->lastOfMonth()->format('d');

		$is_page_realizadas = session('preview')['realizadas'];
		$this->realizadas = isset($is_page_realizadas) ? (int) $is_page_realizadas : 0;

		$cc = session('preview')['cc'] ?? false;
		$weekref = session('preview')['week_ref'];
		$_month = substr($weekref, 0, 2);
		$_year = substr($weekref, 4, 2);

		$_dt = Carbon::create("{$_year}-{$_month}-01");
		$this->month_ref = $_dt->format("Y-m");

		if ($cc) {
			$filename = "/previews/{$cc}_{$weekref}_faturamento.json";
			$data_exists = Storage::exists($filename);

			if ($data_exists && !$is_page_realizadas) {
				$data = Storage::get($filename);
				$faturamento = json_decode($data, true);

				$this->companies = $faturamento['option_value'];
				$this->lastOfMonth = $this->companies[0]['last_of_month'];
				$this->edit = false;
			} else {
				$faturamento = Option::where([
					['cc', '=', $cc],
					['week_ref', '=', $weekref],
					['option_name', '=', 'faturamento']
				])->first();
					
				if ($faturamento) {
					$this->companies = unserialize($faturamento->option_value);
					$this->lastOfMonth = $this->companies[0]['last_of_month'];
					$this->edit = true;
				}
			}
		}

		$this->dispatch('update-bar-total', [
			'cc' => $cc,
			'weekref' => $weekref
		]);

		return view('livewire.category.category-invoicing', [
			'cc' => $cc,
			'edit' => $this->edit,
			'month_ref' => $this->month_ref,
			'companies' => $this->companies,
			'lastOfMonth' => $this->lastOfMonth,
			'realizadas_genial' => $this->realizadas_genial,
			'realizadas' => $this->realizadas,
		]);
	}
}
