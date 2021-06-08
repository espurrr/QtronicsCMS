<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.bitrix24_forms
 *
 * @copyright   Copyright (C) Bitrix24
 * @link        https://bitrix24.com
 * @version     1.0.0
 */

defined('_JEXEC') or die;

jimport('joomla.event.plugin');

class plgSystemBitrix24_Forms extends JPlugin
{

	public function plgSystemBitrix24_Forms(&$subject, $params) {
		parent::__construct($subject, $params);
		plgSystemBitrix24_Forms_Container::set($this);
 	}

	/*
	 * Hook on every page load.
	 */
	public function onBeforeRender() {
		$app = JFactory::getApplication();
		if ($app->isSite() && $this->params->get('show_chat') == 1) {
			$document = JFactory::getDocument();
			$document->addScriptDeclaration($this->params->get('chat_code'));
		}
		//check for known plugins
		if ($this->params->get('b24c_enable') == 1) {
			$request = JRequest::get('request');
			if (
				isset($request['option']) && $request['option'] == 'com_virtuemart' &&
				isset($request['view']) && $request['view'] == 'cart' &&
				isset($request['task']) && $request['task'] == 'updatecart'
			) {
				require('bitrix24_processing.php');
				B24Processing::processing_virtuemart();
			}
			elseif (
				isset($request['option']) && $request['option'] == 'com_hikashop' &&
				isset($request['view']) && $request['view'] == 'checkout'
			) {
				require('bitrix24_processing.php');
				B24Processing::processing_hikashop();
			}
			elseif (
				isset($request['option']) && $request['option'] == 'com_j2store' &&
				isset($request['view']) && $request['view'] == 'checkout' &&
				isset($request['order_id']) && $request['order_id'] > 0
			) {
				require('bitrix24_processing.php');
				B24Processing::processing_j2store($request['order_id']);
			}
			elseif (
				isset($request['option']) && $request['option'] == 'com_jshopping' &&
				isset($request['controller']) && $request['controller'] == 'checkout' &&
				isset($request['task']) && $request['task'] == 'finish'
			) {
				require('bitrix24_processing.php');
				B24Processing::processing_jshopping();
			}
		}
	}

	/*
	 * Filter content for replace link to form.
	 */
	public function onContentPrepare($context, &$article, &$params, $limitstart=0) {
		if (preg_match_all('#https://([^/]+)/pub/form/([\d]+)_[^/]+/([^/]+)/#i', $article->text, $matches)) {
			foreach ($matches[0] as $i => $match) {
				$article->text = str_replace($match,
									$this->process_get_form_js(
												$matches[1][$i],
												$matches[3][$i],
												$matches[2][$i]
											),
									$article->text);
			}
		}
	}

	/*
	 * Get JS for form.
	 */
	private function process_get_form_js($portal, $code, $id) {
		static $count = 0;
		static $lang = null;
		if ($lang === null) {
			$lang =& JFactory::getLanguage()->getTag();
			if (strpos($lang, '-') !== false) {
				list($lang) = explode('-', $lang);
			}
		}
		$count++;
		return '<div id="bx24_form_inline_' . $count . '"></div>
				<script id="bx24_form_inline" data-skip-moving="true">
					(function(w,d,u,b){w[\'Bitrix24FormObject\']=b;w[b] = w[b] || function(){arguments[0].ref=u;
					(w[b].forms=w[b].forms||[]).push(arguments[0])};
					if(w[b][\'forms\']) return;
					s=d.createElement(\'script\');r=1*new Date();s.async=1;s.src=u+\'?\'+r;
					h=d.getElementsByTagName(\'script\')[0];h.parentNode.insertBefore(s,h);
					})(window,document,\'https://' . $portal . '/bitrix/js/crm/form_loader.js\',\'b24form\');
					b24form({"id":"' . $id . '","lang":"' . $lang . '","sec":"' . $code . '","type":"inline",
					"node": document.getElementById(\'bx24_form_inline_' . $count . '\')});
				</script>';
	}
}


class plgSystemBitrix24_Forms_Container
{
	private static $object = null;

	public static function set($object)
	{
		self::$object = $object;
	}

	public static function get()
	{
		return self::$object;
	}
}