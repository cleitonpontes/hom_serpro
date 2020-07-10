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
            $this->processaItemRecolhimento($xpath,'//deducao['.$i.']/itemRecolhimento',$model);
            $this->processaPredoc($xpath,'//deducao['.$i.']/predoc',$model);
        }

    }

    public function processaItemRecolhimento(\DOMXPath $xpath,string $query,Model $model)
    {

        $params["sfded_id"] = $model->id;
        $modelName = "App\Models\SfItemRecolhimento";
        $itemRecolhimento = $xpath->query($query);

        $no = $itemRecolhimento->item(0)->childNodes;

        foreach($no as $value => $item){
            $no = $item->childNodes->length;
            ($no <= 1) ? $params[strtolower($item->nodeName)]=$item->nodeValue : '';
        }

        $model = new $modelName;
        $model = $model->newInstance($params);
        $model->save($params);

        return $model;
    }

    public function processaPredoc(\DOMXPath $xpath,string $query,Model $model)
    {
        $params["sfded_id"] = $model->id;
        $modelName = "App\Models\SfPredoc";
        $preDoc = $xpath->query($query);

        $no = $preDoc->item(0)->childNodes;

        foreach($no as $value => $item){
            $no = $item->childNodes->length;
            ($no <= 1) ? $params[strtolower($item->nodeName)]=$item->nodeValue : $params['tipo']= $item->nodeName;

            $preDocFilho = $item->childNodes;
            if($preDocFilho->length > 0) {
                foreach ($preDocFilho as $value => $item) {
                    $noFilho = [];
                    $no = $item->childNodes->length;
                    ($no <= 1 && $item->nodeName != '#text') ? $params[strtolower($item->nodeName)]=$item->nodeValue : $noFilho = ['i' => $value + 1,'tagName'=>$item->nodeValue];
                    //PAREI AQUI
                    dd($noFilho);
                    if(!empty($noFilho)){
                        $this->processaDomicilioBancario($xpath,'//deducao['.$i.']/predoc',$model);
                    }
                }
            }

        }

        dd($params);


        $model = new $modelName;
        $model = $model->newInstance($params);
        $model->save($params);

        return $model;
    }

    public function processaDomicilioBancario(\DOMXPath $xpath,string $query,Model $model)
    {

        $params["sfded_id"] = $model->id;
        $modelName = "App\Models\SfDomicilioBancario";
        $itemRecolhimento = $xpath->query($query);

        $no = $itemRecolhimento->item(0)->childNodes;

        foreach($no as $value => $item){
            $no = $item->childNodes->length;
            ($no <= 1) ? $params[strtolower($item->nodeName)]=$item->nodeValue : '';
        }

        $model = new $modelName;
        $model = $model->newInstance($params);
        $model->save($params);

        return $model;
    }

}
