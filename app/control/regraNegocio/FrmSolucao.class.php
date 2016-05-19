<?php

class FrmSolucao extends TPage
{

    private $form;

    function __construct()
    {
    
        parent::__construct();
        
        //$this->form = new TQuickForm('form_Solucao');
        $this->form = new BootstrapFormWrapper(new TQuickForm('form_solucao'));
        $this->form->class = 'tform'; // change CSS class
        $this->form->style = 'display: table;width:70%'; // change style
        $notebook  = new TNotebook(200, 350);
        
        $this->form->add($notebook);
        
          parent::include_css('app/resources/custom-notebook.css');
        
        // creates the containers for each notebook page
        $tbl_solucao = new TTable;
        $tbl_pAplicacao = new TTable;
        $tbl_sAplicacao = new TTable;
        $tbl_solucao->style = "margin: 4px";
        $tbl_pAplicacao->style = "margin: 4px";
        $tbl_sAplicacao->style = "margin: 4px";
        
              
        
        
        $notebook->appendPage('Totais Solução', $tbl_solucao);
        $notebook->appendPage('1ª Aplicação', $tbl_pAplicacao);
        $notebook->appendPage('2ª Aplicação', $tbl_sAplicacao);
        
        
        
        ####Campos primeira página######
        
        $idSolucao        = new THidden('idSolucao');
        $cmbReproducao = new TDBCombo('idReproducao','dbwf','Reproducao','idReproducao','idReproducao');
        $reproducao = new TEntry('idReproducao');
        
        //$cmbReproducao->setChangeAction();
        
        $soro = new TEntry('totalSoro');
        $soro->setTip('Quantidade total de soro usada nesta Reprodução!');
        $hipofise = new TEntry('totalHipofise');
        $hipofise->setTip('Quantidade total de hipófise usada nesta Reprodução');
        $pVolTotalAplicado = new TEntry('pVolTotalAplicado');
        $pVolTotalAplicado->setTip('Volume total a ser aplicado na primeira aplicação');
        $sVolTotalAplicado = new TEntry('sVolTotalAplicado');
        $sVolTotalAplicado->setTip('Volume total a ser aplicado na segunda aplicação');
        
        $this->form->addQuickField('REPRODUÇÃO..:',$cmbReproducao);
        $this->form->addQuickField('SORO..:', $soro);
        $this->form->addQuickField('HIPÓFISE..:',$hipofise);
        $this->form->addQuickField('VOL. TOTAL 1ª AP..:',$pVolTotalAplicado);
        $this->form->addQuickField('VOL. TOTAL 2ª AP..:', $sVolTotalAplicado);
        
        
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        //$this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onClear')), 'bs:plus-sign green');
        
        
        
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 60%; position:absolut; margin-left:5%;';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Aplicação de Hormônios', $this->form));
        
        parent::add($container);   
        
   }
   
   public function onSave()
   {
   
       try
       {
           TTransaction::open('dbwf');
           $this->form->validate();
           
           $object = new Solucao;
           $data = $this->form->getData();
           
           $object->store();
           $this->form->setData($data);
           TTransaction::close();
           
       }
       catch(Exception $e)
       {
            new TMessage('Erro ao gravar o registro!', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); //
       }
   }

}


?>
