<?php

namespace Elastica;
use Elastica\Exception\InvalidException;

/**
 * Elastica Request object
 *
 * @category Xodoa
 * @package Elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
class Request extends Param
{
    const POST = 'POST';
    const PUT = 'PUT';
    const GET = 'GET';
    const DELETE = 'DELETE';

    /**
     * Request path
     *
     * @var string Request path
     */
    protected $_path;

    /**
     * Request method (use const's)
     *
     * @var string Request method (use const's)
     */
    protected $_method;

    /**
     * Data array
     *
     * @var array Data array
     */
    protected $_data;

    /**
     * Query params
     *
     * @var array Query params
     */
    protected $_query;

    /**
     * @var \Elastica\Connection
     */
    protected $_connection;

    /**
     * Construct
     *
     * @param string              $path       Request path
     * @param string              $method     OPTIONAL Request method (use const's) (default = self::GET)
     * @param array               $data       OPTIONAL Data array
     * @param array               $query      OPTIONAL Query params
     * @param \Elastica\Connection $connection OPTIONAL Connection object
     */
    public function __construct($path, $method = self::GET, $data = array(), array $query = array(), Connection $connection = null)
    {
        $this->setPath($path);
        $this->setMethod($method);
        $this->setData($data);
        $this->setQuery($query);

        if ($connection) {
            $this->setConnection($connection);
        }
    }

    /**
     * Sets the request method. Use one of the for consts
     *
     * @param  string           $method Request method
     * @return \Elastica\Request Current object
     */
    public function setMethod($method)
    {
        return $this->setParam('method', $method);
    }

    /**
     * Get request method
     *
     * @return string Request method
     */
    public function getMethod()
    {
        return $this->getParam('method');
    }

    /**
     * Sets the request data
     *
     * @param  array            $data Request data
     * @return \Elastica\Request
     */
    public function setData($data)
    {
        return $this->setParam('data', $data);
    }

    /**
     * Return request data
     *
     * @return array Request data
     */
    public function getData()
    {
        return $this->getParam('data');
    }

    /**
     * Sets the request path
     *
     * @param  string           $path Request path
     * @return \Elastica\Request Current object
     */
    public function setPath($path)
    {
        return $this->setParam('path', $path);
    }

    /**
     * Return request path
     *
     * @return string Request path
     */
    public function getPath()
    {
        return $this->getParam('path');
    }

    /**
     * Return query params
     *
     * @return array Query params
     */
    public function getQuery()
    {
        return $this->getParam('query');
    }

    /**
     * @param  array            $query
     * @return \Elastica\Request
     */
    public function setQuery(array $query = array())
    {
        return $this->setParam('query', $query);
    }

    /**
     * @param  \Elastica\Connection $connection
     * @return \Elastica\Request
     */
    public function setConnection(Connection $connection)
    {
        $this->_connection = $connection;

        return $this;
    }

    /**
     * Return Connection Object
     *
     * @return \Elastica\Connection
     */
    public function getConnection()
    {
        if (empty($this->_connection)) {
            throw new InvalidException('No valid connection object set');
        }

        return $this->_connection;
    }

    /**
     * Sends request to server
     *
     * @return \Elastica\Response Response object
     */
    public function send()
    {
        $transport = $this->getConnection()->getTransportObject();

        // Refactor: Not full toArray needed in exec?
        return $transport->exec($this, $this->getConnection()->toArray());
    }
}
