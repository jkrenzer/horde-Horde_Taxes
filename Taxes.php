<?php
/**
 * @package Horde_Module_Taxes
 *
 * Copyright 2006-2007 Duck <duck@obala.net>
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * $Horde: incubator/Horde_Taxes/Taxes.php,v 1.16 2010/02/01 10:32:07 jan Exp $
 */

/**
 * Horde_Autoloader
 */
require_once 'Horde/Autoloader.php';

/**
 * The Horde_Taxes:: class and Horde_TaxesMapper:: provides Rdo
 * implementations for handling taxes data.
 *
 * @author  Duck <duck@obala.net>
 * @package Horde_Module_Taxes
 */
class Horde_Taxes extends Horde_Rdo_Base {
}

/**
 * @package Horde_Module_Taxes
 */
class Horde_TaxesMapper extends Horde_Rdo_Mapper {

    /**
     * The name of the SQL table.
     *
     * @var string
     */
    protected $_table = 'horde_taxes';

    /**
     */
    public function getAdapter()
    {
        $GLOBALS['conf']['sql']['adapter'] = 'pdo_' . $GLOBALS['conf']['sql']['phptype'];

        return Horde_Db_Adapter::factory($GLOBALS['conf']['sql']);
    }

    /**
     * Return field metadata.
     */
    public function formMeta()
    {
        $data = array();

        $data['id'] = array(
                'humanName' => _("Id"));

        $data['name'] = array(
                'humanName' => _("Name"));

        $data['value'] = array(
                'humanName' => _("Value"));

        $data['sort'] = array(
                'humanName' => _("Sort"),
                'type' => 'int');

        return $data;
    }

    /**
     * Get all taxes.
     */
    public function getAll()
    {
        return $this->find();
    }

    /**
     * Get tax.
     */
    public function getOne($id)
    {
        return $this->findOne(array('id' => $id));
    }

    /**
     * Get cached Taxes
     *
     * @return array taxes data
     */
    static public function getTaxes()
    {
        static $data;

        if ($data) {
            return $data;
        }

        if (($data = self::getCache())) {
            return $data;
        }

        $mapper = new Horde_TaxesMapper();
        $query = new Horde_Rdo_Query($mapper);
        $query->sortBy('sort DESC');

        $data = array();
        foreach (new Horde_Rdo_List($query) as $value) {
            $data[$value->id]['id'] = (int)$value->id;
            $data[$value->id]['name'] = $value->name;
            $data[$value->id]['value'] = (float)$value->value;
        }

        self::setCache($data);
        return $data;
    }

    /**
     * Wrap to expire cache
     */
    public function create($fields)
    {
        parent::create($fields);
        self::expireCache('Horde_Taxes');
    }

    public function update($object, $fields = null)
    {
        parent::update($object, $fields);
        self::expireCache('Horde_Taxes');
    }

    public function delete($object)
    {
        parent::delete($object);
        self::expireCache('Horde_Taxes');
    }

    /**
     * Retreive cache
     *
     * @return mixed    data or false cache key not exists
     */
    private static function getCache()
    {
        $data = $GLOBALS['injector']
            ->getInstance('Horde_Cache')
            ->get('Horde_Taxes', $GLOBALS['conf']['cache']['driver']);
        if ($data) {
            return unserialize($data);
        } else {
            return false;
        }
    }

    /**
     * Store cache
     *
     * @param  mixed  $data Data to save
     *
     * @return boolean if the cache was saved
     */
    private static function setCache($data)
    {
        return $GLOBALS['injector']
            ->getInstance('Horde_Cache')
            ->set('Horde_Taxes', serialize($data));
    }

    /**
     * Delete cache
     *
     * @return boolean if the cache was expired
     */
    private static function expireCache()
    {
        return $GLOBALS['injector']
            ->getInstance('Horde_Cache')
            ->expire('Horde_Taxes');
    }
}
