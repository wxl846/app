<?php
/**
 * UrlSummary
 *
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   Swaagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * curation-cms
 *
 * No description provided (generated by Swagger Codegen https://github.com/swagger-api/swagger-codegen)
 *
 * OpenAPI spec version: 0.1.1-SNAPSHOT
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 *
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Swagger\Client\CurationCMS\Models;

use \ArrayAccess;

/**
 * UrlSummary Class Doc Comment
 *
 * @category    Class
 * @package     Swagger\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class UrlSummary implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'UrlSummary';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'url' => 'string',
        'title' => 'string',
        'type' => 'string',
        'description' => 'string',
        'original_url' => 'string',
        'video_url' => 'string',
        'video_secure_url' => 'string',
        'video_type' => 'string',
        'video_height' => 'int',
        'video_width' => 'int',
        'image_url' => 'string',
        'image_height' => 'int',
        'image_width' => 'int',
        'date_retrieved' => '\Swagger\Client\CurationCMS\Models\Instant',
        'source_attribution' => 'string',
        'source_url' => 'string',
        'attribution' => '\Swagger\Client\CurationCMS\Models\Attribution'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerFormats = [
        'url' => null,
        'title' => null,
        'type' => null,
        'description' => null,
        'original_url' => null,
        'video_url' => null,
        'video_secure_url' => null,
        'video_type' => null,
        'video_height' => 'int32',
        'video_width' => 'int32',
        'image_url' => null,
        'image_height' => 'int32',
        'image_width' => 'int32',
        'date_retrieved' => null,
        'source_attribution' => null,
        'source_url' => null,
        'attribution' => null
    ];

    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'url' => 'url',
        'title' => 'title',
        'type' => 'type',
        'description' => 'description',
        'original_url' => 'originalUrl',
        'video_url' => 'videoUrl',
        'video_secure_url' => 'videoSecureUrl',
        'video_type' => 'videoType',
        'video_height' => 'videoHeight',
        'video_width' => 'videoWidth',
        'image_url' => 'imageUrl',
        'image_height' => 'imageHeight',
        'image_width' => 'imageWidth',
        'date_retrieved' => 'dateRetrieved',
        'source_attribution' => 'sourceAttribution',
        'source_url' => 'sourceUrl',
        'attribution' => 'attribution'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'url' => 'setUrl',
        'title' => 'setTitle',
        'type' => 'setType',
        'description' => 'setDescription',
        'original_url' => 'setOriginalUrl',
        'video_url' => 'setVideoUrl',
        'video_secure_url' => 'setVideoSecureUrl',
        'video_type' => 'setVideoType',
        'video_height' => 'setVideoHeight',
        'video_width' => 'setVideoWidth',
        'image_url' => 'setImageUrl',
        'image_height' => 'setImageHeight',
        'image_width' => 'setImageWidth',
        'date_retrieved' => 'setDateRetrieved',
        'source_attribution' => 'setSourceAttribution',
        'source_url' => 'setSourceUrl',
        'attribution' => 'setAttribution'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'url' => 'getUrl',
        'title' => 'getTitle',
        'type' => 'getType',
        'description' => 'getDescription',
        'original_url' => 'getOriginalUrl',
        'video_url' => 'getVideoUrl',
        'video_secure_url' => 'getVideoSecureUrl',
        'video_type' => 'getVideoType',
        'video_height' => 'getVideoHeight',
        'video_width' => 'getVideoWidth',
        'image_url' => 'getImageUrl',
        'image_height' => 'getImageHeight',
        'image_width' => 'getImageWidth',
        'date_retrieved' => 'getDateRetrieved',
        'source_attribution' => 'getSourceAttribution',
        'source_url' => 'getSourceUrl',
        'attribution' => 'getAttribution'
    ];

    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    public static function setters()
    {
        return self::$setters;
    }

    public static function getters()
    {
        return self::$getters;
    }

    const TYPE_FANDOM_ARTICLE = 'FANDOM_ARTICLE';
    const TYPE_STORY_STREAM = 'STORY_STREAM';
    const TYPE_TWEET = 'TWEET';
    const TYPE_INSTAGRAM = 'INSTAGRAM';
    const TYPE_OTHER = 'OTHER';
    

    
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getTypeAllowableValues()
    {
        return [
            self::TYPE_FANDOM_ARTICLE,
            self::TYPE_STORY_STREAM,
            self::TYPE_TWEET,
            self::TYPE_INSTAGRAM,
            self::TYPE_OTHER,
        ];
    }
    

    /**
     * Associative array for storing property values
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['url'] = isset($data['url']) ? $data['url'] : null;
        $this->container['title'] = isset($data['title']) ? $data['title'] : null;
        $this->container['type'] = isset($data['type']) ? $data['type'] : null;
        $this->container['description'] = isset($data['description']) ? $data['description'] : null;
        $this->container['original_url'] = isset($data['original_url']) ? $data['original_url'] : null;
        $this->container['video_url'] = isset($data['video_url']) ? $data['video_url'] : null;
        $this->container['video_secure_url'] = isset($data['video_secure_url']) ? $data['video_secure_url'] : null;
        $this->container['video_type'] = isset($data['video_type']) ? $data['video_type'] : null;
        $this->container['video_height'] = isset($data['video_height']) ? $data['video_height'] : null;
        $this->container['video_width'] = isset($data['video_width']) ? $data['video_width'] : null;
        $this->container['image_url'] = isset($data['image_url']) ? $data['image_url'] : null;
        $this->container['image_height'] = isset($data['image_height']) ? $data['image_height'] : null;
        $this->container['image_width'] = isset($data['image_width']) ? $data['image_width'] : null;
        $this->container['date_retrieved'] = isset($data['date_retrieved']) ? $data['date_retrieved'] : null;
        $this->container['source_attribution'] = isset($data['source_attribution']) ? $data['source_attribution'] : null;
        $this->container['source_url'] = isset($data['source_url']) ? $data['source_url'] : null;
        $this->container['attribution'] = isset($data['attribution']) ? $data['attribution'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = [];

        $allowed_values = $this->getTypeAllowableValues();
        if (!in_array($this->container['type'], $allowed_values)) {
            $invalid_properties[] = sprintf(
                "invalid value for 'type', must be one of '%s'",
                implode("', '", $allowed_values)
            );
        }

        return $invalid_properties;
    }

    /**
     * validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {

        $allowed_values = $this->getTypeAllowableValues();
        if (!in_array($this->container['type'], $allowed_values)) {
            return false;
        }
        return true;
    }


    /**
     * Gets url
     * @return string
     */
    public function getUrl()
    {
        return $this->container['url'];
    }

    /**
     * Sets url
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->container['url'] = $url;

        return $this;
    }

    /**
     * Gets title
     * @return string
     */
    public function getTitle()
    {
        return $this->container['title'];
    }

    /**
     * Sets title
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->container['title'] = $title;

        return $this;
    }

    /**
     * Gets type
     * @return string
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $allowed_values = $this->getTypeAllowableValues();
        if (!is_null($type) && !in_array($type, $allowed_values)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'type', must be one of '%s'",
                    implode("', '", $allowed_values)
                )
            );
        }
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets description
     * @return string
     */
    public function getDescription()
    {
        return $this->container['description'];
    }

    /**
     * Sets description
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->container['description'] = $description;

        return $this;
    }

    /**
     * Gets original_url
     * @return string
     */
    public function getOriginalUrl()
    {
        return $this->container['original_url'];
    }

    /**
     * Sets original_url
     * @param string $original_url
     * @return $this
     */
    public function setOriginalUrl($original_url)
    {
        $this->container['original_url'] = $original_url;

        return $this;
    }

    /**
     * Gets video_url
     * @return string
     */
    public function getVideoUrl()
    {
        return $this->container['video_url'];
    }

    /**
     * Sets video_url
     * @param string $video_url
     * @return $this
     */
    public function setVideoUrl($video_url)
    {
        $this->container['video_url'] = $video_url;

        return $this;
    }

    /**
     * Gets video_secure_url
     * @return string
     */
    public function getVideoSecureUrl()
    {
        return $this->container['video_secure_url'];
    }

    /**
     * Sets video_secure_url
     * @param string $video_secure_url
     * @return $this
     */
    public function setVideoSecureUrl($video_secure_url)
    {
        $this->container['video_secure_url'] = $video_secure_url;

        return $this;
    }

    /**
     * Gets video_type
     * @return string
     */
    public function getVideoType()
    {
        return $this->container['video_type'];
    }

    /**
     * Sets video_type
     * @param string $video_type
     * @return $this
     */
    public function setVideoType($video_type)
    {
        $this->container['video_type'] = $video_type;

        return $this;
    }

    /**
     * Gets video_height
     * @return int
     */
    public function getVideoHeight()
    {
        return $this->container['video_height'];
    }

    /**
     * Sets video_height
     * @param int $video_height
     * @return $this
     */
    public function setVideoHeight($video_height)
    {
        $this->container['video_height'] = $video_height;

        return $this;
    }

    /**
     * Gets video_width
     * @return int
     */
    public function getVideoWidth()
    {
        return $this->container['video_width'];
    }

    /**
     * Sets video_width
     * @param int $video_width
     * @return $this
     */
    public function setVideoWidth($video_width)
    {
        $this->container['video_width'] = $video_width;

        return $this;
    }

    /**
     * Gets image_url
     * @return string
     */
    public function getImageUrl()
    {
        return $this->container['image_url'];
    }

    /**
     * Sets image_url
     * @param string $image_url
     * @return $this
     */
    public function setImageUrl($image_url)
    {
        $this->container['image_url'] = $image_url;

        return $this;
    }

    /**
     * Gets image_height
     * @return int
     */
    public function getImageHeight()
    {
        return $this->container['image_height'];
    }

    /**
     * Sets image_height
     * @param int $image_height
     * @return $this
     */
    public function setImageHeight($image_height)
    {
        $this->container['image_height'] = $image_height;

        return $this;
    }

    /**
     * Gets image_width
     * @return int
     */
    public function getImageWidth()
    {
        return $this->container['image_width'];
    }

    /**
     * Sets image_width
     * @param int $image_width
     * @return $this
     */
    public function setImageWidth($image_width)
    {
        $this->container['image_width'] = $image_width;

        return $this;
    }

    /**
     * Gets date_retrieved
     * @return \Swagger\Client\CurationCMS\Models\Instant
     */
    public function getDateRetrieved()
    {
        return $this->container['date_retrieved'];
    }

    /**
     * Sets date_retrieved
     * @param \Swagger\Client\CurationCMS\Models\Instant $date_retrieved
     * @return $this
     */
    public function setDateRetrieved($date_retrieved)
    {
        $this->container['date_retrieved'] = $date_retrieved;

        return $this;
    }

    /**
     * Gets source_attribution
     * @return string
     */
    public function getSourceAttribution()
    {
        return $this->container['source_attribution'];
    }

    /**
     * Sets source_attribution
     * @param string $source_attribution
     * @return $this
     */
    public function setSourceAttribution($source_attribution)
    {
        $this->container['source_attribution'] = $source_attribution;

        return $this;
    }

    /**
     * Gets source_url
     * @return string
     */
    public function getSourceUrl()
    {
        return $this->container['source_url'];
    }

    /**
     * Sets source_url
     * @param string $source_url
     * @return $this
     */
    public function setSourceUrl($source_url)
    {
        $this->container['source_url'] = $source_url;

        return $this;
    }

    /**
     * Gets attribution
     * @return \Swagger\Client\CurationCMS\Models\Attribution
     */
    public function getAttribution()
    {
        return $this->container['attribution'];
    }

    /**
     * Sets attribution
     * @param \Swagger\Client\CurationCMS\Models\Attribution $attribution
     * @return $this
     */
    public function setAttribution($attribution)
    {
        $this->container['attribution'] = $attribution;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     * @param  integer $offset Offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     * @param  integer $offset Offset
     * @param  mixed   $value  Value to be set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     * @param  integer $offset Offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(\Swagger\Client\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        }

        return json_encode(\Swagger\Client\ObjectSerializer::sanitizeForSerialization($this));
    }
}


