<?php
/**
 * FrmEspecie Form
 * @author  <your name here>
 */
class FrmEspecie extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_Especie');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:90%;margin-right:20px'; // change style
        
        // define the form title
        $this->form->setFormTitle('Especie');
        
        $this->form->setFieldsByRow(2);

        // create the form fields
        $idEspecie = new TEntry('idEspecie');
        $nomePopularEspecie = new TEntry('nomePopularEspecie');
        $nomeCientificoEspecie = new TEntry('nomeCientificoEspecie');
        $tamanhoMaximo = new TEntry('tamanhoMaximo');
        $horaGrauInicioReproducao = new TEntry('horaGrauInicioReproducao');
        $qtdeSoroKgPv1 = new TEntry('qtdeSoroKgPv1');
        $qtdeSoroKgPv2 = new TEntry('qtdeSoroKgPv2');
        $QtdeMaximaAplicacoes = new TEntry('QtdeMaximaAplicacoes');
        $idFamiliaEspecie =  new TDBCombo('idFamiliaEspecie','dbwf','familiaespecie','idFamiliaEspecie','descricaoFamilia');
        $tipoEspecie = new TCombo('tipoEspecie');
        $tamanhoMaximo->setTip('Tamanho máximo em metros alcançado pela espécie');
        $tamanhoMaximo->setNumericMask(2,',','.');
        
        $qtdeSoroKgPv1->setNumericMask(2,',','.');
        $qtdeSoroKgPv2->setNumericMask(2,',','.');
        $tipo_especie = array();
        $tipo_especie['c'] = 'Carnívoro';
        $tipo_especie['h'] = 'Herbívoro';
        $tipo_especie['o'] = 'Onívoro';
        $tipoEspecie->addItems($tipo_especie);

        // add the fields
        $this->form->addQuickField('CODIGO...:', $idEspecie,  100 );
        $this->form->addQuickField('NOME...:', $nomePopularEspecie,  200 , new TRequiredValidator);
        $this->form->addQuickField('NOME CIENT...:', $nomeCientificoEspecie,  200 );
        $this->form->addQuickField('TAMANHO...:', $tamanhoMaximo,  200 );
        $this->form->addQuickField('HORA-GRAU...:', $horaGrauInicioReproducao,  100 );
        $this->form->addQuickField('1ª APLICAÇÃO..:', $qtdeSoroKgPv1,  100 );
        $this->form->addQuickField('2ª APLICAÇÃO..:', $qtdeSoroKgPv2,  100 );
        $this->form->addQuickField('NÚMERO DE APLICAÇÕES..:', $QtdeMaximaAplicacoes,  100 );
        $this->form->addQuickField('FAMILIA .....:', $idFamiliaEspecie,  200 , new TRequiredValidator);
        $this->form->addQuickField('TIPO.....:', $tipoEspecie,  200 );

        $nomePopularEspecie->setTip('Informe o nome popular da espécie');
        $nomeCientificoEspecie->setTip('Informe o nome científico da espécie');
        $horaGrauInicioReproducao->setTip('Informe a hora-grau aproximada para início de reprodução');
        $qtdeSoroKgPv1->setTip('Quantidade de Solução injetada na primeira aplicação em miligramas');
        $qtdeSoroKgPv2->setTip('Quantidade de Solução injetada na segunda aplicação em miligramas');
        $QtdeMaximaAplicacoes->setTip('Quantidade máxima de aplicações de hormônio');


        if (!empty($idEspecie))
        {
            $idEspecie->setEditable(FALSE);
        }
        
        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onClear')), 'bs:plus-sign green');
        
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%;position: relative; left:4px';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Cadastro de Espécie', $this->form));
        
        parent::add($container);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('dbwf'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            
            $object = new Especie;  // create an empty object
            $data = $this->form->getData(); // get form data as array
            $object->idEspecie = str_pad($object->idEspecie, 10,"0", STR_PAD_LEFT);
            $object->fromArray( (array) $data); // load the object with data
            $object->tamanhoMaximo = str_replace(',','.',$object->tamanhoMaximo);
            $object->qtdeSoroKgPv1 = str_replace(',','.',$object->qtdeSoroKgPv1);
            $object->qtdeSoroKgPv2 = str_replace(',','.',$object->qtdeSoroKgPv2);
            $object->store(); // save the object
            
            // get the generated idEspecie
            $data->idEspecie = $object->idEspecie;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info','Registro gravado com sucesso');
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('ERRO AO GRAVAR O REGISTRO',$e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear();
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('dbwf'); // open a transaction
                $object = new Especie($key); // instantiates the Active Record
                $object->idEspecie = str_pad($object->idEspecie, 10,"0", STR_PAD_LEFT);
                $object->tamanhoMaximo = str_replace('.',',', $object->tamanhoMaximo);
                $object->qtdeSoroKgPv1 = str_replace('.',',', $object->qtdeSoroKgPv1);
                $object->qtdeSoroKgPv2 = str_replace('.',',', $object->qtdeSoroKgPv2);
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
             new TMessage('error','ERRO AO GRAVAR REGISTRO!' . $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
}
