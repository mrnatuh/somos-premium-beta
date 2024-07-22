<script>
import axios from 'axios';

export default {

    data() {
        return {
            dias_seg_sab: 31,
            dias_dom_fer: 0,
            params: {},
            employees: {},
            he: {},
            cc: null,
            weekref: null,
            total: 0,
            edit: true,
        }
    },

    mounted() {
        let self = this;

        const mo = JSON.parse(document.querySelector('#mo_json').value);

        this.params = mo.parameters;
        this.employees = mo.employees;
        this.he = mo.he;
        this.cc = mo.cc;
        this.weekref = mo.weekref;
        this.dias_seg_sab = mo.dias_seg_sab;
        this.dias_dom_fer = mo.dias_dom_fer;
        this.edit = mo.edit;

        // console.log(this.params);
        this.handleTotalDias();
    },

    methods: {
        formatCurrency(number) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(number);
        },

        formatPercent(number) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'percent',
                currency: 'BRL',
                minimumFractionDigits: 2,
            }).format(number);
        },

        handleTotalDias() {
            this.employees.forEach((item) => {
                this.handleDiasTrabalhados(item);
            });

            this.getTotal();
        },

        getTotal() {
            this.total = 0;

            this.employees.forEach((item) => {
                this.total += item?.tmp_vlr_total_funcionario ?? 0;
            });

            this.save();
            window.autoSave();
        },

        handleDiasTrabalhados(item, update = false) {
            const vlr_salario_bruto = (item.salario / 30) * item.dias_trabalhados;

            item.vlr_salario = this.formatCurrency(item.salario);

            const extras = this.he.filter(it => it.id == item.id);

            let vlr_total_50 = 0;
            let vlr_total_100 = 0;
            let vlr_total_faltas = 0;
            let vlr_total_atrasos = 0;
            let vlr_total_adicional_noturno = 0;
            let dsr = 0;
            let vlr_total_he = 0;

            if (extras.length) {
                let vlr_total_50_tmp = parseInt(extras[0].total_vlr_50.replace(",", "").replace(".", "").replace("R$", ""));
                vlr_total_50 = vlr_total_50_tmp / 100;

                let vlr_total_100_tmp = parseInt(extras[0].total_vlr_100.replace(",", "").replace(".", "").replace("R$", ""));
                vlr_total_100 = vlr_total_100_tmp / 100;

                let vlt_total_adicional_noturno_tmp = parseInt(extras[0].total_vlr_adicional_noturno.replace(",", "").replace(".", "").replace("R$", ""));
                vlr_total_adicional_noturno = vlt_total_adicional_noturno_tmp / 100;

                const vlr_he_total_add = vlr_total_50 + vlr_total_100 + vlr_total_adicional_noturno;

                let total_vlr_faltas_tmp = parseInt(extras[0].total_vlr_faltas.replace(",", "").replace(".", "").replace("R$", ""));
                vlr_total_faltas = total_vlr_faltas_tmp / 100;

                let total_vlr_atrasos_tmp = parseInt(extras[0].total_vlr_atrasos.replace(",", "").replace(".", "").replace("R$", ""));
                vlr_total_atrasos = total_vlr_atrasos_tmp / 100;

                const vlr_he_total_sub = vlr_total_faltas + vlr_total_atrasos;

                dsr = (vlr_total_50 / this.dias_seg_sab) * this.dias_dom_fer;
                dsr += (vlr_total_100 / this.dias_seg_sab) * this.dias_dom_fer;
                dsr += (vlr_total_adicional_noturno / this.dias_seg_sab) * this.dias_dom_fer;

                vlr_total_he = vlr_he_total_add - vlr_he_total_sub;
            }

            item.vlr_dsr = this.formatCurrency(dsr);

            item.vlr_salario_bruto = this.formatCurrency(vlr_salario_bruto);

            const vlr_vr = vlr_salario_bruto * (1 / 100);
            item.vlr_desconto_refeicao = this.formatCurrency(vlr_vr);

            const vlr_vt = vlr_salario_bruto * (6 / 100);
            item.vlr_desconto_vale_transporte = this.formatCurrency(vlr_vt);

            const vrl_vale_transporte = item.vlr_vt == "0,01" ? item.vlr_desconto_vale_transporte : this.formatCurrency(item.vlr_vt);

            item.vrl_vale_transporte = vrl_vale_transporte;

            const vlr_cesta_basica = this.params.cesta_basica * parseInt(item.option_cesta_basica);
            item.vlr_cesta_basica = this.formatCurrency(vlr_cesta_basica);

            const vlr_assistencia_medica_funcionarios = item.plano_saude * this.params.assistencia_medica_titular;

            item.vlr_assistencia_medica_funcionario = this.formatCurrency(vlr_assistencia_medica_funcionarios);

            const vlr_assistencia_medica_dependentes = item.qtde_dependentes * this.params.assistencia_medica_dependentes;

            const vlr_total_assistencia_medica = (vlr_assistencia_medica_funcionarios + vlr_assistencia_medica_dependentes) * parseInt(item.option_assistencia_medica);

            item.vlr_assistencia_medica_dependentes = this.formatCurrency(vlr_assistencia_medica_dependentes);

            const vlr_exames = 0 * this.params.exames;
            item.vlr_exames = this.formatCurrency(vlr_exames);

            const vlr_assistencia_odontologica = (item.odonto + item.odonto_dependentes) * this.params.assistencia_odontologica;

            item.vlr_assistencia_odontologica = this.formatCurrency(vlr_assistencia_odontologica);

            const vlr_contribuicao_sindical = (item.contribuicao_sindical * this.params.contribuicao_sindical) * parseInt(item.option_contribuicao_sindical);
            item.vlr_contribuicao_sindical = this.formatCurrency(vlr_contribuicao_sindical);

            item.vlr_inss = this.formatCurrency((this.params.inss / 100) * (dsr + vlr_salario_bruto));
            item.vlr_fgts = this.formatCurrency((this.params.fgts / 100) * (dsr + vlr_salario_bruto));
            item.vlr_provisao_ferias = this.formatCurrency((this.params.provisao_ferias / 100) * (dsr + vlr_salario_bruto));
            item.vlr_decimo_terceiro = this.formatCurrency((this.params.provisao_decimo_terceiro / 100) * (dsr + vlr_salario_bruto));

            const vlr_total_salario = vlr_salario_bruto + dsr - vlr_vt - vlr_vr;
            item.vlr_total_salario = this.formatCurrency(vlr_total_salario);

            const vlr_total_funcionario = vlr_total_salario + vlr_total_he + vlr_cesta_basica + vlr_total_assistencia_medica + vlr_exames + vlr_assistencia_odontologica;

            item.tmp_vlr_total_funcionario = vlr_total_funcionario;

            item.vlr_total_funcionario = this.formatCurrency(vlr_total_funcionario);

            if (update) this.getTotal();
        },

        save() {
            const obj = {
                cc: this.cc,
                weekref: this.weekref,
                dias_seg_sab: this.dias_seg_sab,
                dias_dom_fer: this.dias_dom_fer,
                params: this.params,
                employees: this.employees,
                total: this.total,
            };

            document.querySelector('#mo_json').value = JSON.stringify(obj);
        }
    }
}

window.toggleLoading = function (show = true) {
    var el = document.querySelector("#live-loading");

    if (show) {
        el.classList.remove("hidden");
        el.classList.add("fixed");
    } else {
        el.classList.remove("fixed");
        el.classList.add("hidden");
    }
}

window.autoSave = function () {
    var data = document.querySelector('#mo_json').value;
    var json = JSON.parse(data);

    toggleLoading();

    axios.post('/categoria/mo', {
        mo_json: data,
    }).then(function (response) {
        console.log('[response]', response.data);

        window.Livewire.dispatch('update-bar-total', {
            cc: json.cc,
            weekref: json.weekref,
        });

        window.setTimeout(function () {
            toggleLoading(false);
        }, 1000);
    }).catch(function (error) {
        console.log(error);
    });
}


</script>

<template>
    <div class="mt-5 p-3">
        <div class="flex flex-shrink-0 flex-grow-0 mb-10 overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th class="w-[100px] border-b">
                            <div class="flex flex-col text-[#B1B1B1] text-[14px] font-normal w-[100px]">
                                <span>Dias</span>
                                <span>Seg a Sáb</span>
                            </div>
                        </th>
                        <th class="w-[100px] border-b">
                            <div class="flex flex-col text-[#B1B1B1] text-[14px] font-normal w-[100px]">
                                <span>Domingos</span>
                                <span>e Feriados</span>
                            </div>
                        </th>
                        <th class="w-[100px] border-b">
                            <div class="flex flex-col text-[#B1B1B1] text-[14px] font-normal w-[100px]">
                                <span>Cesta</span>
                                <span>Básica</span>
                            </div>
                        </th>
                        <th class="w-[100px] border-b">
                            <div class="flex flex-col text-[#B1B1B1] text-[14px] font-normal w-[100px]">
                                <span>Assistência</span>
                                <span>Médica</span>
                                <span>Titular</span>
                            </div>
                        </th>
                        <th class="w-[100px] border-b">
                            <div class="flex flex-col text-[#B1B1B1] text-[14px] font-normal w-[100px]">
                                <span>Assistência</span>
                                <span>Médica</span>
                                <span>Dependentes</span>
                            </div>
                        </th>
                        <th class="w-[100px] border-b">
                            <div class="flex flex-col text-[#B1B1B1] text-[14px] font-normal w-[100px]">
                                <span>Exames</span>
                            </div>
                        </th>
                        <th class="w-[100px] border-b">
                            <div class="flex flex-col text-[#B1B1B1] text-[14px] font-normal w-[100px]">
                                <span>Assistência</span>
                                <span>Odontológica</span>
                            </div>
                        </th>
                        <th class="w-[100px] border-b">
                            <div class="flex flex-col text-[#B1B1B1] text-[14px] font-normal w-[100px]">
                                <span>Contribuição</span>
                                <span>Sindical</span>
                            </div>
                        </th>
                        <th class="w-[100px] border-b">
                            <div class="flex flex-col text-[#B1B1B1] text-[14px] font-normal w-[100px]">
                                <span>INSS</span>
                            </div>
                        </th>
                        <th class="w-[100px] border-b">
                            <div class="flex flex-col text-[#B1B1B1] text-[14px] font-normal w-[100px]">
                                <span>FGTS</span>
                            </div>
                        </th>
                        <th class="w-[100px] border-b">
                            <div class="flex flex-col text-[#B1B1B1] text-[14px] font-normal w-[100px]">
                                <span>Provisão</span>
                                <span>Férias</span>
                            </div>
                        </th>
                        <th class="w-[100px] border-b">
                            <div class="flex flex-col text-[#B1B1B1] text-[14px] font-normal w-[100px]">
                                <span>Provisão</span>
                                <span>13</span>
                            </div>
                        </th>
                        <th class="w-[100px] border-b">
                            <div class="flex flex-col text-[#B1B1B1] text-[14px] font-normal w-[100px]">
                                <span>Taxa</span>
                                <span>Vale Transporte</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center w-[100px]">
                            <input
                                class="flex items-center justify-center text-center w-[100px] text-[16px] text-[#404D61] border-0"
                                type="number" min="1" max="31" v-model="dias_seg_sab" @change="handleTotalDias"
                                :disabled="!edit" />
                        </td>
                        <td class="text-center w-[100px]">
                            <input
                                class="flex items-center justify-center text-center w-[100px] text-[16px] text-[#404D61] border-0"
                                type="number" min="1" max="31" v-model="dias_dom_fer" @change="handleTotalDias"
                                :disabled="!edit" />
                        </td>
                        <td class="text-center w-[100px] text-[16px] text-[#404D61]">
                            {{ params.cesta_basica }}
                        </td>
                        <td class="text-center w-[100px] text-[16px] text-[#404D61]">
                            {{ params.assistencia_medica_titular }}
                        </td>
                        <td class="text-center w-[100px] text-[16px] text-[#404D61]">
                            {{ params.assistencia_medica_dependentes }}
                        </td>
                        <td class="text-center w-[100px] text-[16px] text-[#404D61]">
                            {{ params.exames }}
                        </td>
                        <td class="text-center w-[100px] text-[16px] text-[#404D61]">
                            {{ params.assistencia_odontologica }}
                        </td>
                        <td class="text-center w-[100px] text-[16px] text-[#404D61]">
                            {{ params.contribuicao_sindical }}
                        </td>
                        <td class="text-center w-[100px] text-[16px] text-[#404D61]">
                            {{ params.inss }}
                        </td>
                        <td class="text-center w-[100px] text-[16px] text-[#404D61]">
                            {{ params.fgts }}
                        </td>
                        <td class="text-center w-[100px] text-[16px] text-[#404D61]">
                            {{ params.provisao_ferias }}
                        </td>
                        <td class="text-center w-[100px] text-[16px] text-[#404D61]">
                            {{ params.provisao_decimo_terceiro }}
                        </td>
                        <td class="text-center w-[100px] text-[16px] text-[#404D61]">
                            {{ params.taxa_vale_transporte }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-shrink-0 flex-grow-0 w-full mb-10 overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th class="text-[14px] text-[#B1B1B1] font-normal text-left">
                            Nome
                        </th>
                        <th class="text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                            Salário
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Dias</span>
                                <span>Trabalhados</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Cesta</span>
                                <span>Básica</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Assistência</span>
                                <span>Médica</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Número</span>
                                <span>Dependentes</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Assistência</span>
                                <span>Odontológica</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Contribuição</span>
                                <span>Sindical</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Vale</span>
                                <span>Transporte</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Salário</span>
                                <span>Bruto</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Total</span>
                                <span>Funcionário</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>DSR</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Desconto</span>
                                <span>Refeição</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Desconto</span>
                                <span>VT</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Total</span>
                                <span>Salário</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Cesta</span>
                                <span>Básica</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Assistência</span>
                                <span>Médica</span>
                                <span>Funcionário</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Assistência</span>
                                <span>Médica</span>
                                <span>Dependentes</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Exames</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Assistência</span>
                                <span>Odontológica</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Contribuição</span>
                                <span>Sindical</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Vale</span>
                                <span>Transporte</span>
                                <span>Total</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>INSS</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>FGTS</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Provisão</span>
                                <span>Férias</span>
                            </div>
                        </th>
                        <th class="w-[100px]">
                            <div class="flex flex-col text-[14px] text-[#B1B1B1] font-normal w-[100px]">
                                <span>Provisão</span>
                                <span>13</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in employees" :key="index">
                        <td class="text-[14px] text-[#404D61]">
                            {{ item.nome }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_salario }}
                        </td>
                        <td class="text-[14px] text-[#404D61]">
                            <input
                                class="flex items-center justify-center text-center w-[100px] text-[14px] text-[#404D61] border-0"
                                type="number" min="1" max="31" v-model="employees[index].dias_trabalhados"
                                @change="handleDiasTrabalhados(item, true)" :disabled="!edit" />
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            <select class="border-0" v-model="item.option_cesta_basica"
                                @change="handleDiasTrabalhados(item, true)" :disabled="!edit">
                                <option value="0" :selected="!item.option_cesta_basica">Não</option>
                                <option value="1" :selected="item.option_cesta_basica">Sim</option>
                            </select>

                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            <select class="border-0" v-model="item.option_assistencia_medica"
                                @change="handleDiasTrabalhados(item, true)" :disabled="!edit">
                                <option value="0" :selected="!item.option_assistencia_medica">Não</option>
                                <option value="1" :selected="item.option_assistencia_medica">Sim</option>
                            </select>
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.qtde_dependentes }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            <select class="border-0" v-model="item.option_assistencia_odontologica"
                                @change="handleDiasTrabalhados(item, true)" :disabled="!edit">
                                <option value="0" :selected="!item.option_assistencia_odontologica">Não</option>
                                <option value="1" :selected="item.option_assistencia_odontologica">Sim</option>
                            </select>
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            <select class="border-0" v-model="item.option_contribuicao_sindical"
                                @change="handleDiasTrabalhados(item, true)" :disabled="!edit">
                                <option value="0" :selected="!item.option_contribuicao_sindical">Não</option>
                                <option value="1" :selected="item.option_contribuicao_sindical">Sim</option>
                            </select>
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            <select class="border-0" v-model="item.option_vale_transporte"
                                @change="handleDiasTrabalhados(item, true)" :disabled="!edit">
                                <option value="0" :selected="!item.option_vale_transporte">Não</option>
                                <option value="1" :selected="item.option_vale_transporte">Sim</option>
                            </select>
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_salario_bruto }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_total_funcionario }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_dsr }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_desconto_refeicao }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_desconto_vale_transporte }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_total_salario }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_cesta_basica }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_assistencia_medica_funcionario }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_assistencia_medica_dependentes }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_exames }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_assistencia_odontologica }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_contribuicao_sindical }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_vt }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_inss }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_fgts }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_provisao_ferias }}
                        </td>
                        <td class="text-[14px] text-[#404D61] text-center">
                            {{ item.vlr_decimo_terceiro }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
