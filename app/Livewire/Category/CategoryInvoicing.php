<?php

namespace App\Livewire\Category;

use App\Models\Invoicing;
use App\Models\Option;
use App\Models\Preview;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Exception;
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
	public $wait = 0;

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

			$this->redirect('/categoria?filter=faturamento');
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

		$is_page_realizadas = session('preview')['realizadas'] ?? 0;
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
					if (isset($this->companies[0])) {
						$this->lastOfMonth = $this->companies[0]['last_of_month'];
					} else {
						$_month = substr($weekref, 0, 2);
						$_year = substr($weekref, 4, 2);
						$_date_of_preview = Carbon::parse($_year . "-" . $_month . "-01");
						$this->lastOfMonth = (int) $_date_of_preview->lastOfMonth()->format('d');
					}

					// acha os ids das refeições possiveis para o cliente
					$_prices_ids = [];
					$_prices_ids_serv = [];
					$_cgcs = [];
					$_cgcs_a1_genial = [];

					foreach ($this->companies as $c) {
						$_cgcs[$c['id']] = [];

						foreach ($c['prices_options'] as $p) {
							$_prices_ids[$p['id']] = [];
							$_prices_ids_serv[$p['id']] = '';
						}
					}

					$tmp_clients =  DB::connection('mysql_dump')
						->table('CLIENTES')
						->whereIn('A1_CGC', array_keys($_cgcs))
						->get();

					foreach ($tmp_clients as $c) {
						$_cgcs[trim($c->A1_CGC)] = [
							"A1_TABELA" => trim($c->A1_TABELA),
							"A1_CDGENIAL" => trim($c->A1_CDGENIAL),
						];

						$_cgcs_a1_genial[trim($c->A1_CDGENIAL)] = '';
					}

					// acha todos IDS de produto
					$tmp_produtos =  DB::connection('mysql_dump')
						->table('PRODUTOS')
						->whereIn('B1_COD', array_keys($_prices_ids_serv))
						->get();

					foreach ($tmp_produtos as $p) {
						$cod = trim($p->B1_COD);
						$_prices_ids_serv[$cod] = trim($p->B1_XCDGEN);
					}

					// acha todos faturamentos para os produtos
					$tmp_faturamento =  DB::connection('mysql_dump')
						->table('FATURAMENTO')
						->whereIn('ZA3_CDPESS', array_keys($_cgcs_a1_genial))
						->whereIn('ZA3_CDSERV', array_values($_prices_ids_serv))
						->get();


					$_serv_ids_price = array_flip($_prices_ids_serv);

					$_dts_faturamento = [];
					foreach ($tmp_faturamento as $fat) {
						$_dt_fat = trim($fat->ZA3_DTCARP);
						$_serv = trim($fat->ZA3_CDSERV);
						$_price = $_serv_ids_price[$_serv];

						if (!isset($_dts_faturamento[$_price])) {
							$_dts_faturamento[$_price] = [];
						}

						if (!isset($_dts_faturamento[$_price][$_dt_fat])) {
							$_dts_faturamento[$_price][$_dt_fat] = [];
						}

						$_dts_faturamento[$_price][$_dt_fat] = [
							"DT" => $_dt_fat,
							"PRICE" => $_serv_ids_price[$_serv],
							"SERV" => $_serv,
							"QTD" => (int) trim($fat->ZA3_QUANTF),
						];
					}

					foreach ($this->companies as $k_c => $c) {
						foreach ($c['rows'] as $k_c_r => $r) {
							$_d_r = $k_c_r + 1;
							$_dt_r = Carbon::parse("{$_year}-{$_month}-" . ($_d_r < 10 ? '0' . $_d_r : $_d_r))->format("Y-m-d");

							foreach ($r as $k_c_r_s => $r_s) {
								if (isset($r_s['id'])) {
									$_f = $_dts_faturamento[$r_s['id']][$_dt_r] ?? null;
									$this->companies[$k_c]['rows'][$k_c_r][$k_c_r_s]['compare'] = $_f;
								}
							}
						}
					}

					// calculada realizadas
					try {
						$total_executadas = 0;
						$total_previsao = 0;
						foreach ($this->companies as $company) {
							$tmp_prices = array_values($company['prices_vlr']);
							foreach ($tmp_prices as $p) {
								$prices[$p['id']] = $p;
							}

							foreach ($company['rows'] as $row) {
								foreach ($row as $column) {
									if (isset($column['compare'])) {
										if (isset($prices[$column['compare']['PRICE']])) {
											$price = $prices[$column['compare']['PRICE']]['value'] ?? 0;
											$qtd = $column['compare']['QTD'] ?? 0;
											$total_executadas += $price * $qtd;
										}
									} else {
										if (isset($column['id'])) {
											if (isset($prices[$column['id']])) {
												$price = $prices[$column['id']]['value'];
												$qtd = $column['value'];
												$total_previsao += $price * $qtd;
											}
										}
									}
								}
							}
						}

						$total_realizadas = $total_executadas + $total_previsao;

						$realizadas_faturamento_total_filename = "/realizadas/faturamento_total_{$cc}_{$weekref}.txt";
						Storage::put($realizadas_faturamento_total_filename, serialize([
							'total_executadas' => $total_executadas,
							'total_previsao' => $total_previsao,
							'total_realizadas' => $total_realizadas,
						]));
					} catch (Exception $e) {
						// print $e;
					}

					if (!$this->realizadas) {
						$this->edit = true;
					} else {
						$this->edit = false;
					}
				}
			}
		}

		if ($this->wait) {
			$this->edit = false;
		}

		return view('livewire.category.category-invoicing', [
			'cc' => $cc,
			'edit' => $this->edit,
			'month_ref' => $this->month_ref,
			'companies' => $this->companies,
			'lastOfMonth' => $this->lastOfMonth,
			'realizadas' => $this->realizadas,
		]);
	}
}
