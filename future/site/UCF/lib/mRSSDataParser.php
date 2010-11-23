<?php

class mRSSDataParser extends RSSDataParser{

	public function init($args)
	{
		parent::init($args);
		
		if (isset($args['MEDIAGROUP_CLASS'])){
			$this->setMediaGroupClass($args['MEDIAGROUP_CLASS']);
		}
	}

	public function setMediaGroupClass($mediaGroupClass)
	{
		if ($mediaGroupClass) {
			if (!class_exists($mediaGroupClass)) {
				throw new Exception("Cannot load class $mediaGroupClass");
			}
			$this->mediaGroupClass = $mediaGroupClass;
		}
	}

	protected function startElement($xml_parser, $name, $attribs)
	{
		$this->data = '';
		switch ($name)
		{
			case 'MEDIA:GROUP':
				$this->elementStack[] = new $this->mediaGroupClass($attribs);
				break;
			default:
				parent::startElement($xml_parser, $name, $attribs);
		}
	}
	
}

?>