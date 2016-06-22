<?php
/**
 * Soro Active Record
 * @author  <your-name-here>
 */
class Soro extends TRecord
{
    const TABLENAME = 'soro';
    const PRIMARYKEY= 'idSoro';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($idSoro = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($idSoro, $callObjectLoad);
        parent::addAttribute('nomeSoro');
        parent::addAttribute('descSoro');
        parent::addAttribute('valorSoro');
        parent::addAttribute('qtdeAplicSoroKvP');
        parent::addAttribute('valorSoroAplicacao');
        parent::addAttribute('idSolucao');
       
    }


}
