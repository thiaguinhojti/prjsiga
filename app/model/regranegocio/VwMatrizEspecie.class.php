<?php
/**
 * VwMatrizEspecie Active Record
 * @author  <your-name-here>
 */
class VwMatrizEspecie extends TRecord
{
    const TABLENAME = 'vw_matriz_especie';
    const PRIMARYKEY= 'cod_matriz';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('especie');
        parent::addAttribute('matriz');
        parent::addAttribute('sexo');
    }


}
