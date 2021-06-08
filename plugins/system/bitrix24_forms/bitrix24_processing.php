<?php

require('bitrix24_class.php');

/**
 * Bitrix24 connector's processing.
 *
 * @version     1.0.0
 * @author      Bitrix24
 * @copyright   2016 Bitrix24
 * @link        https://bitrix24.com
 */
class B24Processing
{
	/**
	 * Processing for plugin VirtueMart.
	 * @see https://extensions.joomla.org/extension/virtuemart
	 */
	public function processing_virtuemart() {
		$database = JFactory::getDBO();
		//get last order, because we don't know current order
		$query = "SELECT * FROM `#__virtuemart_orders` ORDER BY `virtuemart_order_id` DESC LIMIT 1;";
		$database->setQuery($query);
		if ($order = $database->loadObject()) {
			$query = "SELECT * FROM `#__virtuemart_order_userinfos` WHERE `virtuemart_order_id`={$order->virtuemart_order_id} AND `address_type`='BT' LIMIT 1;";
			$database->setQuery($query);
			if ($info = $database->loadObject()) {
				//get country
				$info->virtuemart_country_name = '';
				if ($info->virtuemart_country_id) {
					$query = "SELECT * FROM `#__virtuemart_countries` WHERE `virtuemart_country_id`=" . intval($info->virtuemart_country_id) . ";";
					$database->setQuery($query);
					if ($country = $database->loadObject()) {
						$info->virtuemart_country_id = $country->country_2_code;
						$info->virtuemart_country_name = $country->country_name;
					}
				}
				//get state
				$info->virtuemart_state_name = '';
				if ($info->virtuemart_state_id) {
					$query = "SELECT * FROM `#__virtuemart_states` WHERE `virtuemart_state_id`=" . intval($info->virtuemart_state_id) . ";";
					$database->setQuery($query);
					if ($state = $database->loadObject()) {
						$info->virtuemart_state_id = $state->state_2_code;
						$info->virtuemart_state_name = $state->state_name;
					}
				}
				//get currency
				if ($order->order_currency) {
					$query = "SELECT * FROM `#__virtuemart_currencies` WHERE `virtuemart_currency_id`=" . intval($order->order_currency) . ";";
					$database->setQuery($query);
					if ($currency = $database->loadObject()) {
						$order->order_currency = $currency->currency_code_3;
					}
				}
				//send data
				B24Connector::getCurrent()->sendActivity(array(
					'AGENT' => array(
						'ORIGIN_ID' => $order->virtuemart_user_id > 0 ? $order->virtuemart_user_id : $info->email,
						'NAME' => $info->first_name ? $info->first_name : 'Guest',
						'LAST_NAME' => $info->last_name ? $info->last_name : 'Guest',
						'PHONE' => $info->phone_1 ? $info->phone_1 : $info->phone_2,
						'EMAIL' => $info->email,

						'ADDRESS_COUNTRY_CODE' => $info->virtuemart_country_id,
						'ADDRESS_COUNTRY' => $info->virtuemart_country_name,
						'ADDRESS_CITY' => $info->city,
						'ADDRESS_POSTAL_CODE' => $info->zip,
						'ADDRESS_PROVINCE' => $info->virtuemart_state_name,
						'ADDRESS' => $info->address_1 . "\n" . $info->address_2
					),
					'ACTIVITY' => array(
						'ORIGIN_ID' => $order->virtuemart_order_id,
						'NUMBER' => $order->virtuemart_order_id,
						'SUBJECT' => 'New order',
						'DESCRIPTION' => 'New order',
						'RESULT_SUM' => $order->order_total,
						'RESULT_CURRENCY_ID' => $order->order_currency,
						'EXTERNAL_URL' => '/administrator/index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id=' . $order->virtuemart_order_id,
					)
				));
			}
		}
	}

	/**
	 * Processing for plugin HikaShop.
	 * @see https://extensions.joomla.org/extension/hikashop
	 */
	public function processing_hikashop() {
		$database = JFactory::getDBO();
		//get last order, because we don't know current order
		$query = "SELECT `order_id` FROM `" . hikashop_table('order') . "` ORDER BY `order_id` DESC LIMIT 1;";
		$database->setQuery($query);
		if ($order = $database->loadRow()) {
			$orderClass = hikashop_get('class.order');
			//get order info
			if ($order = $orderClass->loadFullOrder($order[0], false, false)) {
				$currency = unserialize($order->order_currency_info);
				$customer = $order->customer;
				$billing = (array)$order->billing_address;
				//send data
				B24Connector::getCurrent()->sendActivity(array(
					'AGENT' => array(
						'ORIGIN_ID' => $customer ? $customer->user_cms_id : $order->order_id . '_1',
						'NAME' => isset($billing['address_firstname']) ? $billing['address_firstname'] : 'Guest',
						'LAST_NAME' => isset($billing['address_lastname']) ? $billing['address_lastname'] : 'Guest',
						'PHONE' => $billing['address_telephone'] ? $billing['address_telephone'] : $billing['address_telephone2'],
						'EMAIL' => $customer->email,

						'ADDRESS_COUNTRY_CODE' => $billing['address_country_code_2'],
						'ADDRESS_COUNTRY' => $billing['address_country'],
						'ADDRESS_CITY' => $billing['address_city'],
						'ADDRESS_POSTAL_CODE' => $billing['address_post_code'],
						'ADDRESS_PROVINCE' => $billing['address_state'],
						'ADDRESS' => $billing['address_street'] . "\n" . $billing['address_street2']
					),
					'ACTIVITY' => array(
						'ORIGIN_ID' => $order->order_id,
						'NUMBER' => $order->order_id,
						'SUBJECT' => 'New order',
						'DESCRIPTION' => 'New order',
						'RESULT_SUM' => $order->order_full_price,
						'RESULT_CURRENCY_ID' => $currency ? $currency->currency_code : null,
						'EXTERNAL_URL' => '/administrator/?option=com_hikashop&ctrl=order&task=edit&cid=' . $order->order_id,
					)
				));
			}
		}
	}

	/**
	 * Processing for plugin j2store.
	 * @see https://extensions.joomla.org/extension/j2store
	 * @param int $id order id
	 */
	public function processing_j2store($id) {
		$id = intval($id);
		$database = JFactory::getDBO();
		//get order info
		$query = "SELECT * FROM `#__j2store_orders` WHERE `order_id`={$id};";
		$database->setQuery($query);
		if ($order = $database->loadObject()) {
			//get billing info
			$query = "SELECT * FROM `#__j2store_orderinfos` WHERE `order_id`={$id};";
			$database->setQuery($query);
			if ($info = $database->loadObject()) {
				//get country info
				if ($info->billing_country_id > 0) {
					$query = "SELECT * FROM `#__j2store_countries` WHERE `j2store_country_id`=" . intval($info->billing_country_id) . ";";
					$database->setQuery($query);
					if ($country = $database->loadObject()) {
						$info->billing_country_id = $country->country_isocode_2;
						$info->billing_country_name = $country->country_name;
					}
				}
				//send data
				B24Connector::getCurrent()->sendActivity(array(
					'AGENT' => array(
						'ORIGIN_ID' => $order->user_id ? $order->user_id : $order->order_id . '_1',
						'NAME' => $info->billing_first_name ? $info->billing_first_name : 'Guest',
						'LAST_NAME' => $info->billing_last_name ? $info->billing_last_name : 'Guest',
						'PHONE' => $info->billing_phone_1 ? $info->billing_phone_1 : $info->billing_phone_2,
						'EMAIL' => $order->user_email,

						'ADDRESS_COUNTRY_CODE' => $info->billing_country_id,
						'ADDRESS_COUNTRY' => $info->billing_country_name,
						'ADDRESS_CITY' => $info->billing_city,
						'ADDRESS_POSTAL_CODE' => $info->billing_zone_id,
						'ADDRESS_PROVINCE' => $info->billing_zone_name,
						'ADDRESS' => $info->billing_address_1 . "\n" . $info->billing_address_2
					),
					'ACTIVITY' => array(
						'ORIGIN_ID' => $order->j2store_order_id,
						'NUMBER' => $order->j2store_order_id,
						'SUBJECT' => 'New order',
						'DESCRIPTION' => 'New order',
						'RESULT_SUM' => $order->order_total,
						'RESULT_CURRENCY_ID' => $order->currency_code,
						'EXTERNAL_URL' => '/administrator/index.php?option=com_j2store&view=order&id=' . $order->j2store_order_id,
					)
				));
			}
		}
	}

	/**
	 * Processing for plugin JoomShopping.
	 * @see https://extensions.joomla.org/extensions/extension/e-commerce/shopping-cart/joomshopping
	 */
	public function processing_jshopping() {
		$database = JFactory::getDBO();
		//get last order, because we don't know current order
		$query = "SELECT * FROM `#__jshopping_orders` ORDER BY `order_id` DESC LIMIT 1;";
		$database->setQuery($query);
		if ($order = $database->loadObject()) {
			//get country info
			if ($order->country > 0) {
				$query = "SELECT * FROM `#__jshopping_countries` WHERE `country_id`=" . intval($order->country) . ";";
				$database->setQuery($query);
				if ($country = (array)$database->loadObject()) {
					$order->country = $country['country_code_2'];
					$lang = JFactory::getLanguage();
					if (isset($country['name_' . $lang->getTag()])) {
						$order->country_name = $country['name_' . $lang->getTag()];
					} else {
						foreach ($country as $k => $v) {
							if (strpos($k, 'name_') === 0) {
								$order->country_name = $v;
								break;
							}
						}
					}
				}
			}
			//send data
			B24Connector::getCurrent()->sendActivity(array(
				'AGENT' => array(
					'ORIGIN_ID' => $order->user_id ? $order->user_id : $order->email,
					'NAME' => $order->f_name ? $order->f_name : 'Guest',
					'LAST_NAME' => $order->l_name ? $order->l_name : 'Guest',
					'PHONE' => $order->phone ? $order->phone : $order->mobil_phone,
					'EMAIL' => $order->email,

					'ADDRESS_COUNTRY_CODE' => $order->country,
					'ADDRESS_COUNTRY' => $order->country_name,
					'ADDRESS_CITY' => $order->city,
					'ADDRESS_POSTAL_CODE' => $order->zip,
					'ADDRESS_PROVINCE' => $order->state,
					'ADDRESS' => $order->street
				),
				'ACTIVITY' => array(
					'ORIGIN_ID' => $order->order_id,
					'NUMBER' => $order->order_id,
					'SUBJECT' => 'New order',
					'DESCRIPTION' => 'New order',
					'RESULT_SUM' => $order->order_total,
					'RESULT_CURRENCY_ID' => $order->currency_code,
					'EXTERNAL_URL' => '/administrator/index.php?option=com_jshopping&controller=orders&task=show&order_id=' . $order->order_id,
				)
			));
		}
	}
}

/**
 * Bitrix24 connector's gate.
 *
 * @version     1.0.0
 * @author      Bitrix24
 * @copyright   2016 Bitrix24
 * @link        https://bitrix24.com
 */
class B24Gate
{

	/**
	 * Save config.
	 * @param string $name
	 * @param string $value
	 */
	public static function saveConfig($name, $value)
	{
		$plugin = plgSystemBitrix24_Forms_Container::get();
		$plugin->params->set($name, $value);
	}

	/**
	 * Get config.
	 * @param string $name
	 * @return mixed
	 */
	public static function getConfig($name)
	{
		$plugin = plgSystemBitrix24_Forms_Container::get();
		return $plugin->params->get($name);
	}

	/**
	 * Get title of connector.
	 * @return string
	 */
	public static function getConnectorName()
	{
		$config = JFactory::getConfig();
		return ($val = $config->get('sitename')) ? $val : 'Joomla';
	}

	/**
	 * Get id of connector.
	 * @return string
	 */
	public static function getConnectorId()
	{
		return 'JOOMLA';
	}

	/**
	 * Get host of connector.
	 * @return string
	 */
	public static function getConnectorHost()
	{
		return ($val = JURI::base()) ? $val : 'http://localhost';
	}
}
