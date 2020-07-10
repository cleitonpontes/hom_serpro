<?php


namespace App\XML;
use App\Models\Contratosfpadrao;
use Illuminate\Database\Eloquent\Model;
use DOMDocument;
use Illuminate\Support\Facades\DB;

class PadroesExecSiafi
{

    public function __construct(){

    }

    public function processamento(string $xmlSiafi,Contratosfpadrao $contratosfpadrao)
    {

        $document = new DOMDocument('1.0', 'utf-8');
        $document->loadXML($xmlSiafi);
        $xpath = new \DOMXPath($document);

        DB::beginTransaction();
        try {
//            $modDadosBasicos = $this->processaDadosBasicos($xpath,'dadosBasicos','//dadosBasicos/*',$contratosfpadrao);
//            $modDocOrigem = $this->processaDocOrigem($xpath,'docOrigem','//dadosBasicos/docOrigem',$modDadosBasicos);
            $modDeducao = $this->processaDeducao($xpath,'deducao','//documentoHabil/deducao',$contratosfpadrao);

            DB::commit();

        } catch (\Exception $exc) {
            DB::rollback();
           // return redirect()->back()->with('warning',"$exc");
        }


    }

    public function processaDadosBasicos(\DOMXPath $xpath,string $tagName,string $query,Model $model): ?object
    {
        $params[$model->getTable()."_id"] = $model->id;
        $modelName = "App\Models\Sf".(ucfirst($tagName));
        $no = $xpath->query($query);

        foreach($no as $value => $item){
            $no = $item->childNodes->length;
            ($no <= 1) ? $params[strtolower($item->nodeName)]=$item->nodeValue : '';
        }

        $model = new $modelName;
        $model = $model->newInstance($params);
        $model->save($params);
        return $model;
    }

    public function processaDocOrigem(\DOMXPath $xpath,string $tagName,string $query,Model $model)
    {
        $params = [];
        $modelName = "App\Models\Sf".(ucfirst($tagName));
        $no = $xpath->query($query);

        foreach($no as $key => $item){
            $params[$key][$model->getTable()."_id"] = $model->id;
            $noAtual = $item->childNodes;
            foreach($noAtual as $value => $item){
                $no = $item->childNodes->length;
                ($no <= 1) ? $params[$key][strtolower($item->nodeName)]=$item->nodeValue : '';
            }
        }
        //dd($params);

        $model = new $modelName;
        $model = $model->newInstance($params);
        $model->save($params);
    }

    public function processaDeducao(\DOMXPath $xpath,string $tagName,string $query,Model $model)
    {
        $params = [];
        $modelName = "App\Models\Sf".(ucfirst($tagName));
        $no = $xpath->query($query);

        foreach($no as $key => $item){
            $params[$key][$model->getTable()."_id"] = $model->id;
            $noAtual = $item->childNodes;

            foreach($noAtual as $value => $item){
                $no = $item->childNodes->length;
                ($no <= 1) ? $params[$key][strtolower($item->nodeName)]=$item->nodeValue : '';
            }

        }

        foreach ($params as $key => $valor){
            $i = $key+1;
            $model = new $modelName;
            $model = $model->newInstance($valor);
            $model->save($valor);
            $this->processaItemRecolhimento($xpath,'itemRecolhimento','//deducao['.$i.']/itemRecolhimento',$model);
            dump('passei');
        }
        dd('teste');

    }

    public function processaItemRecolhimento(\DOMXPath $xpath,string $tagName,string $query,Model $model)
    {

        $params["sfded_id"] = $model->id;
        $modelName = "App\Models\Sf".(ucfirst($tagName));
        $itemRecolhimento = $xpath->query($query);

        $no = $itemRecolhimento->item(0)->childNodes;
        foreach($no as $value => $item){
            $no = $item->childNodes->length;
            ($no <= 1) ? $params[strtolower($item->nodeName)]=$item->nodeValue : '';
        }
        //PAREI AQUI
        dd($params);

        DB::beginTransaction();
        try {
            $model = new $modelName;
            $model = $model->newInstance($params);
            $model->save($params);
            dd($model);
            DB::commit();

        } catch (\Exception $exc) {
            DB::rollback();
            dd($exc->getMessage());
        }

        return $model;
    }


}
