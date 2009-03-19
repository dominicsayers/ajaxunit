<?php
//	---------------------------------------------------------------------------
//									ajaxUnitAPI
//	---------------------------------------------------------------------------
/**
 * @package		ajaxUnit
 * @author		Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license		http://www.opensource.org/licenses/cpal_1.0 Common Public Attribution License Version 1.0 (CPAL) license
 * @link		http://www.dominicsayers.com
 * @version		0.1 - First attempt
 */
interface ajaxUnitAPI {
	/*.public.*/	const	PACKAGE				= 'ajaxUnit',

							ACTION_PARSE		= 'parse',
							ACTION_CONTROL		= 'control',
							ACTION_SUITE		= 'suite',
							ACTION_DUMMY		= 'dummyrun',
							ACTION_JAVASCRIPT	= 'js',
							ACTION_CSS			= 'css',
							ACTION_ABOUT		= 'about',
							ACTION_SOURCECODE	= 'source';
}
//	End of interface ajaxUnitAPI
?>