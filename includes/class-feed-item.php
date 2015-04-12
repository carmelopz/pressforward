<?php

class PF_Feed_Item_Object {
	protected $data = array();

	public function __construct( $item_url, $item_title, $post_type = false ) {
		if (!$post_type) {
			$this->post_type = pf_feed_item_post_type();
		}
		$this->tag_taxonomy = pf_feed_item_tag_taxonomy();
		$this->set_up_item( $item_url, $item_title );

	}
    /**
     * Magic methods are apparently not super perfomant.
     * Avoid using them if you don't have to. Devs should
     * prefer the custom getters and setters that follow.
     */
    public function __isset( $key ) {
        return isset( $this->data[$key] );
    }
    public function __get( $key ) {
        return $this->get( $key );
    }
    public function __set( $key, $value ) {
        $this->set($key, $value);
    }

    //Setters and getters

    /**
     * Set a property for the object.
     *
     * @param string $key   Key to access the property
     * @param any $value    Value to store in the property.
     *
     */
    public function set( $key, $value ) {
        $value = apply_filters('pf_feed_item_property_'.$key, $value, $this);
        if ( 0 === strpos($key, 'item_') ) {
        	$key = str_replace('item_', '', $key);
        }
        if ( method_exists($this,$f='set_'.$key) ){
            $value = call_user_func(array( $this, $f ), $value);
        }        
        $this->data[$key] = $value;
    }
    /**
     * Get an untreated property of the object.
     *
     * This function will retrieve the exact stored value
     * of a property within the object. If you want properties
     * that have been treated in accordance with their accepted
     * use then use the specific getter for that property type.
     *
     * @param  string $key  The name of the property.
     * @return any          Property value.
     */
    public function get( $key ) {
        if ( 0 === strpos($key, 'item_') ) {
            $key = str_replace('item_', '', $key);
        }
        if ( method_exists($this,$f='get_'.$key) ){
            $value = call_user_func(array( $this, $f ));
        }
        return isset( $this->data[$key] ) ? $this->data[$key] : null;
    }

    private function set_up_item( $item_url, $item_title ) {
    	$this->set( 'title', $item_title);
    	$this->set( 'link', $item_url );
    	$this->set( 'id', $this->create_hash_id( $item_url, $item_title ) );
    }

    private function create_hash_id($url, $title){
		$hash = md5($url . $title); 
		return $hash;
	}

	private function set_date( $date ){
		if ( is_array( $date ) ) {
			$date_obj = DateTime::createFromFormat( $date['format'], $date['raw'] );
			$this->set( 'date_obj', $date_obj );
			return $date_obj->format('Y-m-d');
		} else {
			return $date;
		}
	}

	public function set_tags( $tags ) {
		if ( is_array( $tags ) ){
			$this->set( 'tags_array', $tags );
			$tag_string = implode(',', $tags);
			$this->set( 'tags_string', $tag_string );
		} else {
			$tag_string = $tags;
			$this->set( 'tags_string', $tags );
		}
		return $tag_string;
	}

	private function get_tags() {
		$tags = $this->get('tags_array');
		if ( isset( $tags ) && is_array( $tags ) ){
			return implode(',' $tags);
		} else {
			return $this->get('tags_string');
		}
	}

	private function set_content( $content ) {
		$content_obj = new pf_htmlchecker($contet);
		return $content_obj->closetags($content);
	}

}
