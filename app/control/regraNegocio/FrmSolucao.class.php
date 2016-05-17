<?php

class FrmSolucao extends TPage
{


    function __construct()
    {
    
        parent::__construct();
        
        $notebook  = new TNotebook(400, 250);
        
          parent::include_css('app/resources/custom-notebook.css');
        
        // creates the containers for each notebook page
        $page1 = new TTable;
        $page2 = new TTable;
        $page3 = new TTable;
        $page1->style = "margin: 4px";
        $page2->style = "margin: 4px";
        $page3->style = "margin: 4px";
        
        $frame_solucao = new TFrame(NULL,300);
        
        
        
        $notebook->appendPage('Totais Solução', $page1);
        $notebook->appendPage('1ª Aplicação', $page2);
        $notebook->appendPage('2ª Aplicação', $page3);
        
        ####Campos primeira página######
        
        $cmbReproducao = new TDBCombo('reproducao_id','dbwf','Reproducao','idReproducao','idReproducao');
        $reproducao = new TEntry('idReproducao');
        
        $cmbReproducao->setChangeAction();
        
        $soro = new TEntry('totalSoro');
        $soro->setTip('Quantidade Total de Soro usada nesta Reprodução!');
        $hipofise = new TEntry('totalHipofise');
        $pVolTotalAplicado = new TEntry('pVolTotalAplicado');
        $sVolTotalAplicado = new TEntry('sVolTotalAplicado');
        
        ####Campos segunda página####
        
        $page1->addRow();
        
        
        
        
        
        
        
        
    }

}


?>
