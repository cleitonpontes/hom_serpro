<?php

namespace App\XML;
use App\Models\Contratosfpadrao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\BackpackUser;

class PadroesExecSiafi
{

    public function retornaXmlSiafi(Contratosfpadrao $contratosfpadrao){
        $xml = new Execsiafi();
        $user = BackpackUser::find($contratosfpadrao->user_id);
        $xmlSiafi = $xml->consultaDh($user,$contratosfpadrao->codugemit, env('AMBIENTE_SIAFI'), $contratosfpadrao->anodh, $contratosfpadrao);

        return $xmlSiafi;
    }

    public function importaDadosSiafi(string $xmlSiafi,Contratosfpadrao $contratosfpadrao)
    {


        $params['dtemis'] = date("Y-m-d H:i:s");

        $msgErro = 'Erro ao consultar WebService do SIAFI';

        $xml = simplexml_load_string(str_replace(':', '', $xmlSiafi));
        $json = json_encode($xml);
        $objSiafi = json_decode($json);


        $retornoSIAFI = $objSiafi->soapHeader->ns2EfetivacaoOperacao->resultado;

        if(isset($objSiafi->soapBody->ns3cprDHDetalharDHResponse)){

            $body = $objSiafi->soapBody->ns3cprDHDetalharDHResponse->cprDhDetalharResposta;
            if($retornoSIAFI == 'SUCESSO') {

                if (isset($body->documentoHabil)){
                    $resultado = $this->processamento($body->documentoHabil, $contratosfpadrao);
                    $params['situacao'] = 'I';
                    $params['msgretorno'] = 'Importado com Sucesso!';
                    if (!$resultado) {
                        $params['situacao'] = 'E';
                        $params['msgretorno'] = 'Erro ao tentar importar!';
                    }
                }else{
                    $msgErro = $body->mensagem->txtMsg;
                    $params['situacao'] = 'E';
                    $params['msgretorno'] = $msgErro;
                }
            }else{
                $msgErro = $objSiafi->soapBody->soapFault->faultstring;
                $params['situacao'] = 'E';
                $params['msgretorno'] = $msgErro;
            }

        }else{
            $msgErro = 'Horario não permitido para consultar Documento Hábil.';
            $params['situacao'] = 'E';
            $params['msgretorno'] = $msgErro;
        }
        return $params;

    }

    public function processamento(object $arraySiafi,Contratosfpadrao $contratosfpadrao)
    {
        $arrayElemento = [];
        $retorno = false;
        DB::beginTransaction();
        try {

                if(isset($arraySiafi->dadosBasicos) && !is_array($arraySiafi->dadosBasicos)){
                    $arrayElemento[0] = $arraySiafi->dadosBasicos;
                    $this->processaDadosBasicos($arrayElemento,'SfDadosBasicos',$contratosfpadrao);
                }elseif(isset($arraySiafi->dadosBasicos)){
                    $this->processaDadosBasicos($arraySiafi->dadosBasicos,'SfDadosBasicos',$contratosfpadrao);
                }

                if(isset($arraySiafi->pco) && !is_array($arraySiafi->pco)){
                    $arrayElemento[0] = $arraySiafi->pco;
                    $this->processaPco($arrayElemento,'SfPco',$contratosfpadrao);
                }elseif(isset($arraySiafi->pco)){
                    $this->processaPco($arraySiafi->pco,'SfPco',$contratosfpadrao);
                }

                if(isset($arraySiafi->pso) && !is_array($arraySiafi->pso)){
                    $arrayElemento[0] = $arraySiafi->pso;
                    $this->processaPso($arrayElemento,'SfPso',$contratosfpadrao);
                }elseif(isset($arraySiafi->pso)){
                    $this->processaPso($arraySiafi->pso,'SfPso',$contratosfpadrao);
                }

                if(isset($arraySiafi->credito) && !is_array($arraySiafi->credito)){
                    $arrayElemento[0] = $arraySiafi->credito;
                    $modCredito = $this->processaObjetoModel($arrayElemento,'SfCredito',$contratosfpadrao);
                }elseif(isset($arraySiafi->credito)){
                    $modCredito = $this->processaObjetoModel($arraySiafi->credito,'SfCredito',$contratosfpadrao);
                }

                if(isset($arraySiafi->outrosLanc) && !is_array($arraySiafi->outrosLanc)){
                    $arrayElemento[0] = $arraySiafi->outrosLanc;
                    $modOutrosLanc = $this->processaOutrosLanc($arrayElemento,'SfOutrosLanc',$contratosfpadrao);
                }elseif(isset($arraySiafi->outrosLanc)){
                    $modOutrosLanc = $this->processaOutrosLanc($arraySiafi->outrosLanc,'SfOutrosLanc',$contratosfpadrao);
                }

                if(isset($arraySiafi->deducao) && !is_array($arraySiafi->deducao)){
                    $arrayElemento[0] = $arraySiafi->deducao;
                    $modDeducao = $this->processaDeducao($arrayElemento,'SfDeducao',$contratosfpadrao);
                }elseif(isset($arraySiafi->deducao)){
                    $modDeducao = $this->processaDeducao($arraySiafi->deducao,'SfDeducao',$contratosfpadrao);
                }

                if(isset($arraySiafi->encargo) && !is_array($arraySiafi->encargo)){
                    $arrayElemento[0] = $arraySiafi->encargo;
                    $modEncargos = $this->processaEncargos($arrayElemento,'SfEncargos',$contratosfpadrao);
                }elseif(isset($arraySiafi->encargo)){
                    $modEncargos = $this->processaEncargos($arraySiafi->encargo,'SfEncargos',$contratosfpadrao);
                }

                if(isset($arraySiafi->dadosPgto) && !is_array($arraySiafi->dadosPgto)){
                    $arrayElemento[0] = $arraySiafi->dadosPgto;
                    $modDadosPgto = $this->processaDadosPgto($arrayElemento,'SfDadosPgto',$contratosfpadrao);
                }elseif(isset($arraySiafi->dadosPgto)){
                    $modDadosPgto = $this->processaDadosPgto($arraySiafi->dadosPgto,'SfDadosPgto',$contratosfpadrao);
                }

                if(isset($arraySiafi->despesaAnular) && !is_array($arraySiafi->despesaAnular)){
                    $arrayElemento[0] = $arraySiafi->despesaAnular;
                    $modDespesaAnular = $this->processaDespesaAnular($arrayElemento,'SfDespesaAnular',$contratosfpadrao);
                }elseif(isset($arraySiafi->despesaAnular)){
                    $modDespesaAnular = $this->processaDespesaAnular($arraySiafi->despesaAnular,'SfDespesaAnular',$contratosfpadrao);
                }

                if(isset($arraySiafi->compensacao) && !is_array($arraySiafi->compensacao)){
                    $arrayElemento[0] = $arraySiafi->compensacao;
                    $modCompensacao = $this->processaCompensacao($arrayElemento,'SfCompensacao',$contratosfpadrao);
                }elseif(isset($arraySiafi->compensacao)){
                    $modCompensacao = $this->processaCompensacao($arraySiafi->compensacao,'SfCompensacao',$contratosfpadrao);
                }

                if(isset($arraySiafi->centroCusto) && !is_array($arraySiafi->centroCusto)){
                    $arrayElemento[0] = $arraySiafi->centroCusto;
                    $modCentroCusto = $this->processaCentroCusto($arrayElemento,'SfCentroCusto',$contratosfpadrao);
                }elseif(isset($arraySiafi->centroCusto)){
                    $modCentroCusto = $this->processaCentroCusto($arraySiafi->centroCusto,'SfCentroCusto',$contratosfpadrao);
                }

                if(isset($arraySiafi->docContabilizacao) && !is_array($arraySiafi->docContabilizacao)){
                    $arrayElemento[0] = $arraySiafi->docContabilizacao;
                    $modDocContabilizacao = $this->processaObjetoModel($arrayElemento,'SfDocContabilizacao',$contratosfpadrao);
                }elseif(isset($arraySiafi->docContabilizacao)){
                    $modDocContabilizacao = $this->processaObjetoModel($arraySiafi->docContabilizacao,'SfDocContabilizacao',$contratosfpadrao);
                }

            DB::commit();
            $retorno = true;
        } catch (\Exception $exc) {
            DB::rollback();
        }
        return $retorno;
    }

    public function processaDadosBasicos(array $dadosBasicos,string $className,Model $model): ?object
    {
        $params[$model->getTable()."_id"] = $model->id;
        $modelName = 'App\Models\\'.$className;

        foreach($dadosBasicos as $value => $item){
            $params[$value][$model->getTable()."_id"] = $model->id;
            foreach ($item as $key => $item) {
                (!is_object($item)) ? $params[$value][strtolower($key)] = $item : '';
            }
        }

        foreach($dadosBasicos as $value => $item) {

            $model = new $modelName;
            $model = $model->newInstance($params[$value]);
            $model->save($params[$value]);

            if(isset($item->docOrigem) && !is_array($item->docOrigem)){
                $arrayElemento[0] = $item->docOrigem;
                $this->processaObjetoModel($arrayElemento,'SfDocOrigem',$model);
            }elseif(isset($item->docOrigem)){
                $this->processaObjetoModel($item->docOrigem,'SfDocOrigem',$model);
            }

            if(isset($item->docRelacionado) && !is_array($item->docRelacionado)){
                $arrayElemento[0] = $item->docRelacionado;
                $this->processaObjetoModel($arrayElemento,'SfDocRelacionado',$model);
            }elseif(isset($item->docRelacionado)){
                $this->processaObjetoModel($item->docRelacionado,'SfDocRelacionado',$model);
            }

            if(isset($item->tramite) && !is_array($item->tramite)){
                $arrayElemento[0] = $item->tramite;
                $this->processaObjetoModel($arrayElemento,'SfTramite',$model);
            }elseif(isset($item->tramite)){
                $this->processaObjetoModel($item->tramite,'SfTramite',$model);
            }
        }
        return $model;
    }

    public function processaPco(array $pco,string $className,Model $model): ?object
    {
        $params = [];
        $modelName = 'App\Models\\'.$className;

        foreach($pco as $value => $item){
            $params[$value][$model->getTable()."_id"] = $model->id;
            foreach ($item as $key => $item) {
                (!is_object($item)) ? $params[$value][strtolower($key)] = $item : '';
            }
        }

        foreach($pco as $value => $item) {
            $model = new $modelName;
            $model = $model->newInstance($params[$value]);
            $model->save($params[$value]);

            if(isset($item->pcoItem) && !is_array($item->pcoItem)){
                $arrayElemento[0] = $item->pcoItem;
                $this->processaObjetoModel($arrayElemento,'SfPcoItem',$model);
            }elseif(isset($item->pcoItem)){
                $this->processaObjetoModel($item->pcoItem,'SfPcoItem',$model);
            }

            if(isset($item->cronBaixaPatrimonial) && !is_array($item->cronBaixaPatrimonial)){
                $arrayElemento[0] = $item->cronBaixaPatrimonial;
                $this->processaObjetoModel($arrayElemento,'SfCronBaixaPatrimonial',$model);
            }elseif(isset($item->cronBaixaPatrimonial)){
                $this->processaObjetoModel($item->cronBaixaPatrimonial,'SfCronBaixaPatrimonial',$model);
            }
        }
        return $model;
    }

    public function processaPso(array $pso,string $className,Model $model): ?object
    {
        $params = [];
        $modelName = 'App\Models\\'.$className;

        foreach($pso as $value => $item){
            $params[$value][$model->getTable()."_id"] = $model->id;
            foreach ($item as $key => $item) {
                (!is_object($item)) ? $params[$value][strtolower($key)] = $item : '';
            }
        }

        foreach($pso as $value => $item) {
            $model = new $modelName;
            $model = $model->newInstance($params[$value]);
            $model->save($params[$value]);

            if(isset($item->psoItem) && !is_array($item->psoItem)){
                $arrayElemento[0] = $item->psoItem;
                $this->processaObjetoModel($arrayElemento,'SfPsoItem',$model);
            }elseif(isset($item->psoItem)){
                $this->processaObjetoModel($item->psoItem,'SfPsoItem',$model);
            }
        }

        return $model;
    }

    public function processaOutrosLanc(array $outrosLanc,string $className,Model $model): ?object
    {
        $params = [];
        $modelName = 'App\Models\\'.$className;

        foreach($outrosLanc as $value => $item){
            $params[$value][$model->getTable()."_id"] = $model->id;
            foreach ($item as $key => $item) {
                (!is_object($item)) ? $params[$value][strtolower($key)] = $item : '';
            }
        }

        foreach($outrosLanc as $value => $item) {
            $model = new $modelName;
            $model = $model->newInstance($params[$value]);
            $model->save($params[$value]);

            if(isset($item->cronBaixaPatrimonial) && !is_array($item->cronBaixaPatrimonial)){
                $arrayElemento[0] = $item->cronBaixaPatrimonial;
                $this->processaObjetoModel($arrayElemento,'SfCronBaixaPatrimonial',$model);
            }elseif(isset($item->cronBaixaPatrimonial)){
                $this->processaObjetoModel($item->cronBaixaPatrimonial,'SfCronBaixaPatrimonial',$model);
            }
        }
        return $model;
    }

    public function processaDeducao(array $deducao,string $className,Model $model): ?object
    {
        $params = [];
        $modelName = 'App\Models\\'.$className;

        foreach($deducao as $value => $item){
            $params[$value][$model->getTable()."_id"] = $model->id;
            foreach ($item as $key => $item) {
                (!is_object($item)) ? $params[$value][strtolower($key)] = $item : '';
            }
        }

        foreach($deducao as $value => $item) {

            $model = new $modelName;

            $model = $model->newInstance($params[$value]);
            $model->save($params[$value]);

            if(isset($item->itemRecolhimento) && !is_array($item->itemRecolhimento)){
                $arrayElemento[0] = $item->itemRecolhimento;
                $this->processaObjetoModel($arrayElemento,'SfItemRecolhimento',$model);
            }elseif(isset($item->itemRecolhimento)){
                $this->processaObjetoModel($item->itemRecolhimento,'SfItemRecolhimento',$model);
            }

            if(isset($item->predoc) && !is_array($item->predoc)){
                $arrayElemento[0] = $item->predoc;
                $this->processaPredoc($arrayElemento,'SfPredoc',$model);
            }elseif(isset($item->predoc)){
                $this->processaPredoc($item->predoc,'SfPredoc',$model);
            }

            if(isset($item->acrescimo) && !is_array($item->acrescimo)){
                $arrayElemento[0] = $item->acrescimo;
                $this->processaObjetoModel($arrayElemento,'SfAcrescimo',$model);
            }elseif(isset($item->acrescimo)){
                $this->processaObjetoModel($item->acrescimo,'SfAcrescimo',$model);
            }

            if(isset($item->relPcoItem) && !is_array($item->relPcoItem)){
                $arrayElemento[0] = $item->relPcoItem;
                $this->processaObjetoModel($arrayElemento,'SfRelPcoItem',$model);
            }elseif(isset($item->relPcoItem)){
                $this->processaObjetoModel($item->relPcoItem,'SfRelPcoItem',$model);
            }

            if(isset($item->relPsoItem) && !is_array($item->relPsoItem)){
                $arrayElemento[0] = $item->relPsoItem;
                $this->processaObjetoModel($arrayElemento,'SfRelPsoItem',$model);
            }elseif(isset($item->relPsoItem)){
                $this->processaObjetoModel($item->relPsoItem,'SfRelPsoItem',$model);
            }

            if(isset($item->relCredito) && !is_array($item->relCredito)){
                $arrayElemento[0] = $item->relCredito;
                $this->processaObjetoModel($arrayElemento,'SfRelCredito',$model);
            }elseif(isset($item->relCredito)){
                $this->processaObjetoModel($item->relCredito,'SfRelCredito',$model);
            }
        }
        return $model;
    }

    public function processaEncargos(array $encargos,string $className,Model $model): ?object
    {
        $params = [];
        $modelName = 'App\Models\\'.$className;

        foreach($encargos as $value => $item){
            $params[$value][$model->getTable()."_id"] = $model->id;
            foreach ($item as $key => $item) {
                (!is_object($item)) ? $params[$value][strtolower($key)] = $item : '';
            }
        }

        foreach($encargos as $value => $item) {
            $model = new $modelName;
            $model = $model->newInstance($params[$value]);
            $model->save($params[$value]);

            if(isset($item->itemRecolhimento) && !is_array($item->itemRecolhimento)){
                $arrayElemento[0] = $item->itemRecolhimento;
                $this->processaObjetoModel($arrayElemento,'SfItemRecolhimento',$model);
            }elseif(isset($item->itemRecolhimento)){
                $this->processaObjetoModel($item->itemRecolhimento,'SfItemRecolhimento',$model);
            }

            if(isset($item->predoc) && !is_array($item->predoc)){
                $arrayElemento[0] = $item->predoc;
                $this->processaPredoc($arrayElemento,'SfPredoc',$model);
            }elseif(isset($item->predoc)){
                $this->processaPredoc($item->predoc,'SfPredoc',$model);
            }

            if(isset($item->acrescimo) && !is_array($item->acrescimo)){
                $arrayElemento[0] = $item->acrescimo;
                $this->processaObjetoModel($arrayElemento,'SfAcrescimo',$model);
            }elseif(isset($item->acrescimo)){
                $this->processaObjetoModel($item->acrescimo,'SfAcrescimo',$model);
            }
        }
        return $model;
    }

    public function processaDadosPgto(array $dadosPgto,string $className,Model $model): ?object
    {
        $params = [];
        $modelName = 'App\Models\\'.$className;

        foreach($dadosPgto as $value => $item){
            $params[$value][$model->getTable()."_id"] = $model->id;
            foreach ($item as $key => $item) {
                (!is_object($item)) ? $params[$value][strtolower($key)] = $item : '';
            }
        }

        foreach($dadosPgto as $value => $item) {
            $model = new $modelName;
            $model = $model->newInstance($params[$value]);
            $model->save($params[$value]);

            if(isset($item->itemRecolhimento) && !is_array($item->itemRecolhimento)){
                $arrayElemento[0] = $item->itemRecolhimento;
                $this->processaObjetoModel($arrayElemento,'SfItemRecolhimento',$model);
            }elseif(isset($item->itemRecolhimento)){
                $this->processaObjetoModel($item->itemRecolhimento,'SfItemRecolhimento',$model);
            }

            if(isset($item->predoc) && !is_array($item->predoc)){
                $arrayElemento[0] = $item->predoc;
                $this->processaPredoc($arrayElemento,'SfPredoc',$model);
            }elseif(isset($item->predoc)){
                $this->processaPredoc($item->predoc,'SfPredoc',$model);
            }

            if(isset($item->acrescimo) && !is_array($item->acrescimo)){
                $arrayElemento[0] = $item->acrescimo;
                $this->processaObjetoModel($arrayElemento,'SfAcrescimo',$model);
            }elseif(isset($item->acrescimo)){
                $this->processaObjetoModel($item->acrescimo,'SfAcrescimo',$model);
            }
        }
        return $model;
    }

    public function processaDespesaAnular(array $despesaAnular,string $className,Model $model): ?object
    {
        $params = [];
        $modelName = 'App\Models\\'.$className;

        foreach($despesaAnular as $value => $item){
            $params[$value][$model->getTable()."_id"] = $model->id;
            foreach ($item as $key => $item) {
                (!is_object($item)) ? $params[$value][strtolower($key)] = $item : '';
            }
        }

        foreach($despesaAnular as $value => $item) {
            $model = new $modelName;
            $model = $model->newInstance($params[$value]);
            $model->save($params[$value]);

            if(isset($item->despesaAnularItem) && !is_array($item->despesaAnularItem)){
                $arrayElemento[0] = $item->despesaAnularItem;
                $modDesAnularItem = $this->processaObjetoModel($arrayElemento,'SfDespesaAnularItem',$model);
            }elseif(isset($item->despesaAnularItem)){
                $modDesAnularItem = $this->processaObjetoModel($item->despesaAnularItem,'SfDespesaAnularItem',$model);
            }

            if(isset($item->despesaAnularItem->relPcoItem) && !is_array($item->despesaAnularItem->relPcoItem)){
                $arrayElemento[0] = $item->despesaAnularItem->relPcoItem;
                $this->processaObjetoModel($arrayElemento,'SfRelPcoItem',$modDesAnularItem);
            }elseif(isset($item->despesaAnularItem->relPcoItem)){
                $this->processaObjetoModel($item->despesaAnularItem->relPcoItem,'SfRelPcoItem',$modDesAnularItem);
            }

            if(isset($item->despesaAnularItem->relPsoItem) && !is_array($item->despesaAnularItem->relPsoItem)){
                $arrayElemento[0] = $item->despesaAnularItem->relPsoItem;
                $this->processaObjetoModel($arrayElemento,'SfRelPsoItem',$modDesAnularItem);
            }elseif(isset($item->despesaAnularItem->relPsoItem)){
                $this->processaObjetoModel($item->despesaAnularItem->relPsoItem,'SfRelPsoItem',$modDesAnularItem);
            }

            if(isset($item->despesaAnularItem->relCredito) && !is_array($item->despesaAnularItem->relCredito)){
                $arrayElemento[0] = $item->despesaAnularItem->relCredito;
                $this->processaObjetoModel($arrayElemento,'SfRelCredito',$modDesAnularItem);
            }elseif(isset($item->despesaAnularItem->relCredito)){
                $this->processaObjetoModel($item->despesaAnularItem->relCredito,'SfRelCredito',$modDesAnularItem);
            }

            if(isset($item->despesaAnularItem->relEncargo) && !is_array($item->despesaAnularItem->relEncargo)){
                $arrayElemento[0] = $item->despesaAnularItem->relEncargo;
                $this->processaObjetoModel($arrayElemento,'SfRelEncargos',$modDesAnularItem);
            }elseif(isset($item->despesaAnularItem->relEncargo)){
                $this->processaObjetoModel($item->despesaAnularItem->relEncargo,'SfRelEncargos',$modDesAnularItem);
            }
        }
        return $model;
    }

    public function processaCompensacao(array $compensacao,string $className,Model $model): ?object
    {
        $params = [];
        $modelName = 'App\Models\\'.$className;

        foreach($compensacao as $value => $item){
            $params[$value][$model->getTable()."_id"] = $model->id;
            foreach ($item as $key => $item) {
                (!is_object($item)) ? $params[$value][strtolower($key)] = $item : '';
            }
        }

        foreach($compensacao as $value => $item) {
            $model = new $modelName;
            $model = $model->newInstance($params[$value]);
            $model->save($params[$value]);

            if(isset($item->relDeducaoItem) && !is_array($item->relDeducaoItem)){
                $arrayElemento[0] = $item->relDeducaoItem;
                $this->processaObjetoModel($arrayElemento,'SfRelDeducaoItem',$model);
            }elseif(isset($item->relDeducaoItem)){
                $this->processaObjetoModel($item->relDeducaoItem,'SfRelDeducaoItem',$model);
            }

            if(isset($item->relEncargoItem) && !is_array($item->relEncargoItem)){
                $arrayElemento[0] = $item->relEncargoItem;
                $this->processaObjetoModel($arrayElemento,'SfRelEncargoItem',$model);
            }elseif(isset($item->relEncargoItem)){
                $this->processaObjetoModel($item->relEncargoItem,'SfRelEncargoItem',$model);
            }
        }
        return $model;
    }

    public function processaCentroCusto(array $centrocusto,string $className,Model $model): ?object
    {
        $params = [];
        $modelName = 'App\Models\\'.$className;

        foreach($centrocusto as $value => $item){
            $params[$value][$model->getTable()."_id"] = $model->id;
            foreach ($item as $key => $item) {
                (!is_object($item)) ? $params[$value][strtolower($key)] = $item : '';
            }
        }

        foreach($centrocusto as $value => $item) {
            $model = new $modelName;
            $model = $model->newInstance($params[$value]);
            $model->save($params[$value]);

            if(isset($item->relPcoItem) && !is_array($item->relPcoItem)){
                $arrayElemento[0] = $item->relPcoItem;
                $modelRelvlrCc = $this->processaObjetoModel($arrayElemento,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relPcoItem';
                $modelRelvlrCc->update();
            }elseif(isset($item->relPcoItem)){
                $modelRelvlrCc = $this->processaObjetoModel($item->relPcoItem,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relPcoItem';
                $modelRelvlrCc->update();
            }

            if(isset($item->relOutrosLanc) && !is_array($item->relOutrosLanc)){
                $arrayElemento[0] = $item->relOutrosLanc;
                $modelRelvlrCc = $this->processaObjetoModel($arrayElemento,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relOutrosLanc';
                $modelRelvlrCc->update();
            }elseif(isset($item->relOutrosLanc)){
                $modelRelvlrCc = $this->processaObjetoModel($item->relOutrosLanc,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relOutrosLanc';
                $modelRelvlrCc->update();
            }

            if(isset($item->relOutrosLancCronogramaPatrimonial) && !is_array($item->relOutrosLancCronogramaPatrimonial)){
                $arrayElemento[0] = $item->relOutrosLancCronogramaPatrimonial;
                $modelRelvlrCc = $this->processaObjetoModel($arrayElemento,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relOutrosLancCronogramaPatrimonial';
                $modelRelvlrCc->update();
            }elseif(isset($item->relOutrosLancCronogramaPatrimonial)){
                $modelRelvlrCc = $this->processaObjetoModel($item->relOutrosLancCronogramaPatrimonial,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relOutrosLancCronogramaPatrimonial';
                $modelRelvlrCc->update();
            }

            if(isset($item->relPsoItem) && !is_array($item->relPsoItem)){
                $arrayElemento[0] = $item->relPsoItem;
                $modelRelvlrCc = $this->processaObjetoModel($arrayElemento,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relPsoItem';
                $modelRelvlrCc->update();
            }elseif(isset($item->relPsoItem)){
                $modelRelvlrCc = $this->processaObjetoModel($item->relPsoItem,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relPsoItem';
                $modelRelvlrCc->update();
            }

            if(isset($item->relEncargo) && !is_array($item->relEncargo)){
                $arrayElemento[0] = $item->relEncargo;
                $modelRelvlrCc = $this->processaObjetoModel($arrayElemento,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relEncargo';
                $modelRelvlrCc->update();
            }elseif(isset($item->relEncargo)){
                $modelRelvlrCc = $this->processaObjetoModel($item->relEncargo,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relEncargo';
                $modelRelvlrCc->update();
            }

            if(isset($item->relAcrescimoDeducao) && !is_array($item->relAcrescimoDeducao)){
                $arrayElemento[0] = $item->relAcrescimoDeducao;
                $modelRelvlrCc = $this->processaObjetoModel($arrayElemento,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relAcrescimoDeducao';
                $modelRelvlrCc->update();
            }elseif(isset($item->relAcrescimoDeducao)){
                $modelRelvlrCc = $this->processaObjetoModel($item->relAcrescimoDeducao,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relAcrescimoDeducao';
                $modelRelvlrCc->update();
            }

            if(isset($item->relAcrescimoEncargo) && !is_array($item->relAcrescimoEncargo)){
                $arrayElemento[0] = $item->relAcrescimoEncargo;
                $modelRelvlrCc = $this->processaObjetoModel($arrayElemento,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relAcrescimoEncargo';
                $modelRelvlrCc->update();
            }elseif(isset($item->relAcrescimoEncargo)){
                $modelRelvlrCc = $this->processaObjetoModel($item->relAcrescimoEncargo,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relAcrescimoEncargo';
                $modelRelvlrCc->update();
            }

            if(isset($item->relAcrescimoDadosPag) && !is_array($item->relAcrescimoDadosPag)){
                $arrayElemento[0] = $item->relAcrescimoDadosPag;
                $modelRelvlrCc = $this->processaObjetoModel($arrayElemento,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relAcrescimoDadosPag';
                $modelRelvlrCc->update();
            }elseif(isset($item->relAcrescimoDadosPag)){
                $modelRelvlrCc = $this->processaObjetoModel($item->relAcrescimoDadosPag,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relAcrescimoDadosPag';
                $modelRelvlrCc->update();
            }

            if(isset($item->relDespesaAntecipada) && !is_array($item->relDespesaAntecipada)){
                $arrayElemento[0] = $item->relDespesaAntecipada;
                $modelRelvlrCc = $this->processaObjetoModel($arrayElemento,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relDespesaAntecipada';
                $modelRelvlrCc->update();
            }elseif(isset($item->relDespesaAntecipada)){
                $modelRelvlrCc = $this->processaObjetoModel($item->relDespesaAntecipada,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relDespesaAntecipada';
                $modelRelvlrCc->update();
            }

            if(isset($item->relDespesaAnular) && !is_array($item->relDespesaAnular)){
                $arrayElemento[0] = $item->relDespesaAnular;
                $modelRelvlrCc = $this->processaObjetoModel($arrayElemento,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relDespesaAnular';
                $modelRelvlrCc->update();
            }elseif(isset($item->relDespesaAnular)){
                $modelRelvlrCc = $this->processaObjetoModel($item->relDespesaAnular,'Sfrelitemvlrcc',$model);
                $modelRelvlrCc->tipo = 'relDespesaAnular';
                $modelRelvlrCc->update();
            }
        }
        return $model;
    }

    public function processaPredoc(array $predoc,string $className,Model $model): ?object
    {
        $tipo = '';
        $params = [];
        $modelName = 'App\Models\\'.$className;
        foreach($predoc as $value => $item){
            $params[$value] = $this->retonarIdModal($model);
            foreach ($item as $key => $item) {
                (!is_object($item)) ? $params[$value][strtolower($key)] = $item : '';
            }
        }
        foreach($predoc as $value => $item) {
                foreach ($item as $key => $valor) {
                    if($key != 'txtObser'){
                        $tipo = strtolower($key);
                        $params[0]['tipo'] = $tipo;
                        foreach ($valor as $chave => $item){
                            $params[0][strtolower($chave)] = $item;
                        }
                    }
                }
        }
        unset($params[0]['numdomibancfavo']);
        unset($params[0]['numdomibancpgto']);

        $model = new $modelName;
        $model = $model->newInstance($params[0]);
        $model->save($params[0]);

        if($tipo == 'predocob'){
            if (isset($predoc[0]->predocOB->numDomiBancFavo)) {
                $arrayElemento[0] = $predoc[0]->predocOB->numDomiBancFavo;
                $domBancarioFav = $this->processaObjetoModel($arrayElemento, 'SfDomicilioBancario', $model);
                if (!is_null($domBancarioFav)) {
                    $tipo = ['tipo' => 'numDomiBancFavo'];
                    $domBancarioFav->update($tipo);
                }
            }
            if (isset($predoc[0]->predocOB->numDomiBancPgto)) {
                $arrayElemento[0] = $predoc[0]->predocOB->numDomiBancPgto;
                $domBancarioPgto = $this->processaObjetoModel($arrayElemento, 'SfDomicilioBancario', $model);
                if (!is_null($domBancarioPgto)) {
                    $tipo = ['tipo' => 'numDomiBancPgto'];
                    $domBancarioPgto->update($tipo);
                }
            }
        }
        if($tipo == 'predocns') {
            if (isset($predoc[0]->predocNS->numDomiBancPgto)) {
                $arrayElemento[0] = $predoc[0]->predocNS->numDomiBancPgto;
                $domBancarioPgto = $this->processaObjetoModel($arrayElemento, 'SfDomicilioBancario', $model);
                if (!is_null($domBancarioPgto)) {
                    $tipo = ['tipo' => 'numDomiBancPgto'];
                    $domBancarioPgto->update($tipo);
                }
            }
        }
        return $model;
    }

    public function processaObjetoModel(array $objProcessar,string $className,Model $model): ?object
    {
        $params = [];
        $modelName = 'App\Models\\'.$className;

        foreach($objProcessar as $value => $item){
            $params[$value] = $this->retonarIdModal($model);
            foreach ($item as $key => $item) {
                (!is_object($item)) ? $params[$value][strtolower($key)] = $item : '';
            }
        }

        foreach ($params as $key => $value) {
            $model = new $modelName;
            $model = $model->newInstance($value);
            $model->save($value);
        }
        return $model;
    }

    public function retonarIdModal(Model $model)
    {
        $modelName = $model->getTable();

        switch ($modelName) {
            case 'sfdeducao':
                $params["sfded_id"] = $model->id;
                break;
            case 'sfcentrocusto':
                $params["sfcc_id"] = $model->id;
                break;
            case 'sfencargo':
                $params["sfencargos_id"] = $model->id;
                break;
            default:
                $params[$model->getTable()."_id"] = $model->id;
        }
        return $params;
    }
}
