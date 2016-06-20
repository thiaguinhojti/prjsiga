<?php

class FrmSolucao extends TPage
{

    private $form;
    private $datagrid_primeira_aplicacao;
    private $datagrid_segunda_aplicacao;
    function __construct()
    {
    
        parent::__construct();
        
        //$this->form = new TQuickForm('form_Solucao');
        $this->form = new TQuickForm('form_solucao');
        $this->form->class = 'tform'; // change CSS class
        $this->form->style = 'display: table;width:70%; margin-left:50px;'; // change style
        //$this->form->setFormTitle('Aplicação de hormônios');
        $notebook  = new BootstrapNotebookWrapper(new TNotebook(300, 350));
       
        $this->form->add($notebook);
        
          parent::include_css('app/resources/custom-notebook.css');
        
        // creates the containers for each notebook page
        $tbl_solucao = new TTable;
        $tbl_hormonio = new TTable;
        $tbl_soro = new TTable;
        $tbl_pAplicacao = new TTable;
        $tbl_sAplicacao = new TTable;
        $tbl_solucao->style = "margin: 2px;width:100%;";
        $tbl_pAplicacao->style = "margin: 4px";
        $tbl_sAplicacao->style = "margin: 4px";
        $tbl_hormonio->style = "margin: 4px";
        $tbl_soro->style = "margin: 4px";
                
        ####Campos primeira página######
        
        $idSolucao     = new THidden('idSolucao');
        $cmbReproducao = new TDBSeekButton('idReproducao','dbwf',$this->form->getName(),'Reproducao','codigo','idReproducao','cod_reproducao');
        $cod_reproducao = new TEntry('cod_reproducao');
        $pVolTotalAplicado = new TEntry('pVolTotalAplicado');
        $pVolTotalAplicado->setTip('Volume total de solução a ser aplicado na primeira aplicação em mg');
        $sVolTotalAplicado = new TEntry('sVolTotalAplicado');
        $sVolTotalAplicado->setTip('Volume total de solução a ser aplicado na segunda aplicação em mg');
        
        //validações
        $cod_reproducao->setExitAction(new TAction(array($this,'onSelect')));
        
        
         ####Adicionando os campos na aba do notebook####                
        $tbl_solucao->addRowSet(new TLabel(''),$idSolucao);
        $tbl_solucao->addRowSet(new TLabel('REPRODUÇÃO..:'),$cmbReproducao);
        $tbl_solucao->addRowSet(new TLabel('Nº..........:'),$cod_reproducao);
        $tbl_solucao->addRowSet(new TLabel('VOL. TOTAL 1ª APLICAÇÃO..:'),$pVolTotalAplicado);
        $tbl_solucao->addRowSet(new TLabel('VOL. TOTAL 2ª APLICAÇÃO..:'),$sVolTotalAplicado);
        
        
        
        #####Campos segunda Pagina#####
        $lista_soro = new TMultiField('lista_soro');
        $nome_soro = new TEntry('nomeSoro');
        $desc_soro = new TEntry('descSoro');
        $valor_soro = new TEntry('valorSoro');
        $qtdeSoroAplicKvP = new TEntry('qtdeSoroAplicKvP');
        $totalSoroAplicacao = new TEntry('totalSoroAplicacao');
        
        ####Adicionando os campos na aba do notebook####  
        
        $row = $tbl_soro->addRow();
        $cell = $row->addCell(new TLabel('<b><h4>..::SORO::..</h4></b>'));
        $cell->valign  = 'top';
        $lista_soro->setHeight(150);
        $lista_soro->setClass('Soro');
        $lista_soro->addField('nomeSoro','NOME'.':',$nome_soro,150);
        $lista_soro->addField('descSoro','DESCRIÇÃO'.':',$desc_soro,150);
        $lista_soro->addField('valorSoro','VALOR EM R$'.':',$valor_soro,70);
        $lista_soro->addField('qtdeSoroAplicKvP','TOTAL A SER APLICADO'.':',$qtdeSoroAplicKvP,70);
        $lista_soro->addField('totalSoroAplicacao','TOTAL SORO EM R$'.':',$totalSoroAplicacao,70);
        $row=$tbl_soro->addRow();
        $row->addCell($lista_soro);
        
        #####Campos terceira Página#######
        $lista_hormonio = new TMultiField('lista_hormonio');
        $nome_hormonio = new TEntry('nomeHormonio');
        $desc_hormonio = new TEntry('descHormonio');
        $valor_hormonio = new TEntry('valorHormonio');
        $qtdeHormonioAplicKvP = new TEntry('qtdeHormonioAplicKvP');
        $totalHormonioAplicacao = new TEntry('totalHormonioAplicacao');
        
        ####Adicionando os campos na aba do notebook####  
        
        $row = $tbl_hormonio->addRow();
        $cell = $row->addCell(new TLabel('<b><h4>..::HORMÔNIO::..</h4></b>'));
        $cell->valign  = 'top';
        $lista_hormonio->setHeight(150);
        $lista_hormonio->setClass('Hormonio');
        $lista_hormonio->addField('nomeHormonio','NOME'.':',$nome_hormonio,150);
        $lista_hormonio->addField('descHormonio','DESCRIÇÃO'.':',$desc_hormonio,150);
        $lista_hormonio->addField('valorHormonio','VALOR EM R$'.':',$valor_hormonio,70);
        $lista_hormonio->addField('qtdeHormonioAplicKvP','TOTAL A SER APLICADO'.':',$qtdeHormonioAplicKvP,70);
        $lista_hormonio->addField('totalHormonioAplicacao','TOTAL HORMÔNIO EM R$'.':',$totalHormonioAplicacao,70);
        $row=$tbl_hormonio->addRow();
        $row->addCell($lista_hormonio);
        
        #####Campos quarta Página#####
        //$row = $tbl_pAplicacao->addRow();
        $this->datagrid_primeira_aplicacao = new TQuickGrid;
        $this->datagrid_primeira_aplicacao->disableDefaultClick();
        
        $this->datagrid_primeira_aplicacao->addQuickColumn('Número',  'numero',    'center', 70);
        $this->datagrid_primeira_aplicacao->addQuickColumn('Identificação',    'identMatriz',    'right', 180);
        $this->datagrid_primeira_aplicacao->addQuickColumn('Peso', 'pesoMatriz', 'center', 180);
        $this->datagrid_primeira_aplicacao->addQuickColumn('Volume a Injetar',   'fone',    'left', 120);      
        $this->datagrid_primeira_aplicacao->createModel();
        $row1 = $tbl_pAplicacao->addRow();
        $row1->addCell($this->datagrid_primeira_aplicacao);
        //$row->addCell($tbl_pAplicacao);
        
        #####Campos Quinta Página#####
         $this->datagrid_segunda_aplicacao = new TQuickGrid;
        $this->datagrid_segunda_aplicacao->disableDefaultClick();
        
        $this->datagrid_segunda_aplicacao->addQuickColumn('Code',    'code',    'right', 70);
        $this->datagrid_segunda_aplicacao->addQuickColumn('Name',    'name',    'left', 180);
        $this->datagrid_segunda_aplicacao->addQuickColumn('Address', 'address', 'left', 180);
        $this->datagrid_segunda_aplicacao->addQuickColumn('Phone',   'fone',    'left', 120);      
        $this->datagrid_segunda_aplicacao->createModel();
        $row2 = $tbl_sAplicacao->addRow();
        $row2->addCell($this->datagrid_segunda_aplicacao);
        
        
        
        //$this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        //$this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onClear')), 'bs:plus-sign green');
        $notebook->appendPage('Totais Solução', $tbl_solucao);
        $notebook->appendPage('Soro', $tbl_soro);
        $notebook->appendPage('Hormônio', $tbl_hormonio);
        $notebook->appendPage('1ª Aplicação', $tbl_pAplicacao);
        $notebook->appendPage('2ª Aplicação', $tbl_sAplicacao);
        
        $save_button=new TButton('save');
        $save_button->setAction(new TAction(array($this, 'onSave')), _t('Save'));
        $save_button->setImage('fa:floppy-o');
        
        // create an new button (edit with no parameters)
        $new_button=new TButton('new');
        $new_button->setAction(new TAction(array($this, 'onEdit')), _t('New'));
        $new_button->setImage('fa:plus-square green');
        
        $list_button=new TButton('list');
        $list_button->setAction(new TAction(array('FrmListaReproducao','onReload')), _t('Back to the listing'));
        $list_button->setImage('fa:table blue');
        
        $this->form->setFields(array($idSolucao, $cmbReproducao, $cod_reproducao, $pVolTotalAplicado, $sVolTotalAplicado, $lista_soro, $save_button, $new_button, $list_button));
        
       
        
        $buttons = new THBox;
        $buttons->add($save_button);
        $buttons->add($new_button);
        $buttons->add($list_button);
        
        //$this->form->add($buttons);
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 60%;  margin-left:5%;';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Aplicação de Hormônios',$this->form));
        $container->add($buttons);
        
        parent::add($container);   
        
   }
   
   public function onSave()
   {
   
       try
       {
           TTransaction::open('dbwf');
               $this->form->validate(); // validate form data
               $object =$this->form->getData('Solucao');  // create an empty object
               $idReproducao = $object->idSolucao;
               $data = $this->form->getData(); // get form data as array
               $object->fromArray( (array) $data); // load the object with data
               $object->pVolTotalAplicado = str_replace(',','.', $object->pVolTotalAplicado);
               $object->sVolTotalAplicado = str_replace(',','.', $object->sVolTotalAplicado);
               if($object->lista_soro)
               {
                   foreach($object->lista_soro as $soro)
                   {
                   
                       $object->addSoro($soro);
                   
                   }
               }
               if($object->lista_hormonio)
               {
               
                   foreach($object->lista_hormonio as $hormonio)
                   {
                       $object->addHormonio($hormonio);
                   }
               }
               $object->store(); // save the object
                
                // get the generated idSolucao
               $data->idSolucao = $object->idSolucao;
                
               $this->form->setData($data); // fill form data
           TTransaction::close(); // close the transaction
             new TMessage('info','Registro Gravado com sucesso!');
           
       }
       catch(Exception $e)
       {
            new TMessage('Erro ao gravar o registro!', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); //
       }
   }
   public function onReload()
   {
       $this->datagrid_primeira_aplicacao->clear();
       
       try
       {
           
       }
       catch(Exception $e)
       {
           // shows the exception error message
            new TMessage('Erro ao carregar os registros', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();  
       }
   }
   public function onEdit()
   {
   
   }
   public static function onSelect($params)
   {
         $DSPV = 0.5;
         $DDPV = 5.0;
        if(isset($params['cod_reproducao'])&& $params['cod_reproducao'])
        {
            try
            {
                TTransaction::open('dbwf');
                    $rep = new Reproducao($params['idReproducao']);
                    $obj = new StdClass;
                    $obj->pVolTotalAplicado = number_format(($DSPV * $rep->pesoGeralMatriz),2,'.',',');
                    $obj->sVolTotalAplicado = number_format(($DDPV * $rep->pesoGeralMatriz),2,'.',',');
                    TForm::sendData('form_solucao',$obj);
                TTransaction::close();
            
            }
            catch(Exception $e)
            {
                new TMessage('error', '<b>Erro</b> ' . $e->getMessage());
                TTransaction::rollback();
            }
        
        }  
   
   }
}


?>
