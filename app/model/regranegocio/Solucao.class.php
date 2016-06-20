<?php
/**
 * Solucao Active Record
 * @author  <your-name-here>
 */
class Solucao extends TRecord
{
    const TABLENAME = 'solucao';
    const PRIMARYKEY= 'idSolucao';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $reproducao;
    private $soros;
    private $hormonios;
    private $aplicacao_hormonios;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pVolTotalAplicado');
        parent::addAttribute('sVolTotalAplicado');
        parent::addAttribute('idReproducao');
    }

    
    /**
     * Method set_reproducao
     * Sample of usage: $solucao->reproducao = $object;
     * @param $object Instance of Reproducao
     */
    public function set_reproducao(Reproducao $object)
    {
        $this->reproducao = $object;
        $this->idreproducao = $object->id;
    }
    
    /**
     * Method get_reproducao
     * Sample of usage: $solucao->reproducao->attribute;
     * @returns Reproducao instance
     */
    public function get_reproducao()
    {
        // loads the associated object
        if (empty($this->reproducao))
            $this->reproducao = new Reproducao($this->idreproducao);
    
        // returns the associated object
        return $this->reproducao;
    }
    
    
    /**
     * Method addSoro
     * Add a Soro to the Solucao
     * @param $object Instance of Soro
     */
    public function addSoro(Soro $object)
    {
        $this->soros[] = $object;
    }
    
    /**
     * Method getSoros
     * Return the Solucao' Soro's
     * @return Collection of Soro
     */
    public function getSoros()
    {
        return $this->soros;
    }
    
    /**
     * Method addHormonio
     * Add a Hormonio to the Solucao
     * @param $object Instance of Hormonio
     */
    public function addHormonio(Hormonio $object)
    {
        $this->hormonios[] = $object;
    }
    
    /**
     * Method getHormonios
     * Return the Solucao' Hormonio's
     * @return Collection of Hormonio
     */
    public function getHormonios()
    {
        return $this->hormonios;
    }
    
    /**
     * Method addAplicacaoHormonio
     * Add a AplicacaoHormonio to the Solucao
     * @param $object Instance of AplicacaoHormonio
     */
    public function addAplicacaoHormonio(AplicacaoHormonio $object)
    {
        $this->aplicacao_hormonios[] = $object;
    }
    
    /**
     * Method getAplicacaoHormonios
     * Return the Solucao' AplicacaoHormonio's
     * @return Collection of AplicacaoHormonio
     */
    public function getAplicacaoHormonios()
    {
        return $this->aplicacao_hormonios;
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->soros = array();
        $this->hormonios = array();
        $this->aplicacao_hormonios = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
    
        // load the related Soro objects
        $repository = new TRepository('Soro');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $id));
        $this->soros = $repository->load($criteria);
    
        // load the related Hormonio objects
        $repository = new TRepository('Hormonio');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $id));
        $this->hormonios = $repository->load($criteria);
    
        // load the related AplicacaoHormonio objects
        $repository = new TRepository('AplicacaoHormonio');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $id));
        $this->aplicacao_hormonios = $repository->load($criteria);
    
        // load the object itself
        return parent::load($id);
    }

    /**
     * Store the object and its aggregates
     */
    public function store()
    {
        // store the object itself
        parent::store();
    
        // delete the related Soro objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $this->id));
        $repository = new TRepository('Soro');
        $repository->delete($criteria);
        // store the related Soro objects
        if ($this->soros)
        {
            foreach ($this->soros as $soro)
            {
                unset($soro->idSoro);
                $soro->idsolucao = $this->id;
                $soro->store();
            }
        }
        // delete the related Hormonio objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $this->id));
        $repository = new TRepository('Hormonio');
        $repository->delete($criteria);
        // store the related Hormonio objects
        if ($this->hormonios)
        {
            foreach ($this->hormonios as $hormonio)
            {
                unset($hormonio->idHormonio);
                $hormonio->idsolucao = $this->id;
                $hormonio->store();
            }
        }
        // delete the related AplicacaoHormonio objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $this->id));
        $repository = new TRepository('AplicacaoHormonio');
        $repository->delete($criteria);
        // store the related AplicacaoHormonio objects
        if ($this->aplicacao_hormonios)
        {
            foreach ($this->aplicacao_hormonios as $aplicacao_hormonio)
            {
                unset($aplicacao_hormonio->idAplicacaoHormonio);
                $aplicacao_hormonio->idsolucao = $this->id;
                $aplicacao_hormonio->store();
            }
        }
    }

    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        // delete the related Soro objects
        $repository = new TRepository('Soro');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $id));
        $repository->delete($criteria);
        
        // delete the related Hormonio objects
        $repository = new TRepository('Hormonio');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $id));
        $repository->delete($criteria);
        
        // delete the related AplicacaoHormonio objects
        $repository = new TRepository('AplicacaoHormonio');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $id));
        $repository->delete($criteria);
        
    
        // delete the object itself
        parent::delete($id);
    }


}
