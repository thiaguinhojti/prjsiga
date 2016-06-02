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

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('totalSoro');
        parent::addAttribute('totalHipofise');
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
        $this->reproducao_id = $object->id;
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
            $this->reproducao = new Reproducao($this->reproducao_id);
    
        // returns the associated object
        return $this->reproducao;
    }
    


}
