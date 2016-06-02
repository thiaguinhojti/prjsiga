<?php

class FrmBuscaMatriz extends TWindow
{
    private $form;      // form
    private $datagrid;  // datagrid
    private $pageNavigation;
    private $loaded;
    
    /**
     * Class constructor
     * Creates the page, the search form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        parent::setSize(700, 500);
        parent::setTitle('Busca Registros');
        new TSession;
        
        // creates the form
        $this->form = new TQuickForm('form_busca_matriz');
        $this->form->class = 'tform';
        $this->form->setFormTitle('Matrizes');
        
        // create the form fields
        $especie   = new TEntry('especie');
        $especie->setValue(TSession::getValue('matriz_especie'));
        $matriz = new TEntry('matriz');
        $matriz->setValue(TSession::getValue('matriz_numeroChipMatriz'));
        // add the form fields
        $this->form->addQuickField('Especie', $especie,  200);

        // define the form action
        $this->form->addQuickAction('Find', new TAction(array($this, 'onSearch')), 'ico_find.png');
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(230);
        $this->datagrid->enablePopover('Title', 'Name {name}');
        
        // creates the datagrid columns
        $this->datagrid->addQuickColumn('Código', 'cod_matriz', 'right', 40);
        $this->datagrid->addQuickColumn('Especie', 'especie', 'center', 150);
        $this->datagrid->addQuickColumn('Matriz', 'matriz', 'center', 150);
        $this->datagrid->addQuickColumn('sexo', 'sexo', 'center', 40);
        

        // creates two datagrid actions
        $this->datagrid->addQuickAction('Select', new TDataGridAction(array($this, 'onSelect')), 'cod_matriz', 'ico_apply.png');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // creates a container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);
        $container->add($this->datagrid);
        $container->add($this->pageNavigation);
        
        // add the container inside the page
        parent::add($container);
    }
    
    /**
     * method onSearch()
     * Register the filter in the session when the user performs a search
     */
    function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // check if the user has filled the form
        if (isset($data->especie))
        {
            // creates a filter using what the user has typed
            $filter = new TFilter('especie', 'like', "%{$data->especie}%");
            
            // stores the filter in the session
            TSession::setValue('matriz_filter', $filter);
            TSession::setValue('matriz_especie',   $data->especie);
            
            // fill the form with data again
            $this->form->setData($data);
        }
        
        // redefine the parameters for reload method
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * Load the datagrid with the database objects
     */
    function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'samples'
            TTransaction::open('dbwf');
            
            // creates a repository for City
            $repository = new TRepository('VwMatrizEspecie');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (!isset($param['order']))
            {
                $param['order'] = 'cod_matriz';
                $param['direction'] = 'asc';
            }
            
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            
            if (TSession::getValue('matriz_filter'))
            {
                // add the filter stored in the session to the criteria
                $criteria->add(TSession::getValue('matriz_filter'));
            }
            
            // load the objects according to the criteria
            $matrizes = $repository->load($criteria);
            $this->datagrid->clear();
            if ($matrizes)
            {
                foreach ($matrizes as $matriz)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($matriz);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * Executed when the user chooses the record
     */
    public function onSelect($param)
    {
        try
        {
            $key = $param['key'];
            TTransaction::open('dbwf');
            
            // load the active record
            $matriz = new VwMatrizEspecie($key);
            
            // closes the transaction
            TTransaction::close();
            
            $object = new StdClass;
            $object->matriz_id   = $matriz->idMatriz;
            $object->matriz_numero = $matriz->numero;
            
            TForm::sendData('FrmMestreReproducao', $object);
            parent::closeWindow(); // closes the window
        }
        catch (Exception $e) // em caso de exceção
        {
            // clear fields
            $object = new StdClass;
            $object->matriz_id   = '';
            $object->matriz_numero = '';
            TForm::sendData('FrmMestreReproducao', $object);
            
            // undo pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * Shows the page
     */
    function show()
    {
        // if the datagrid was not loaded yet
        if (!$this->loaded)
        {
            $this->onReload();
        }
        parent::show();
    }

}
