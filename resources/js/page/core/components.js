import Vue from 'vue';

//Importation des différents section de l'application  

import mobile from '../application/mobile';
Vue.component('app-mobile', mobile);

import brs from '../application/brs';
Vue.component('app-brs', brs);

import operateur from '../application/operateur';
Vue.component('app-operateur', operateur);

import admin from '../application/admin';
Vue.component('app-admin', admin);

///////////////////////////////////////////
// Composante 
//////////////////////////////////////////

//import messageProfileView from '../components/message-profile-view';
//Vue.component('message-profile-view', messageProfileView);

import modal from '../components/modal';
Vue.component('modal', modal);

import adminMenu from '../components/admin-menu';
Vue.component('admin-menu', adminMenu);

import OperateurMenu from '../components/operateur-menu';
Vue.component('operateur-menu', OperateurMenu);

import mobileMenu from '../components/mobile-menu';
Vue.component('mobile-menu', mobileMenu);

import adminCreateApplication from '../components/admin-create-application';
Vue.component('create-application', adminCreateApplication);

import adminCreateMobile from '../components/admin-create-mobile';
Vue.component('create-mobile', adminCreateMobile);

import adminMobileOptionTrello from '../components/admin-mobile-option-trello';
Vue.component('mobile-option-trello', adminMobileOptionTrello);

import adminMobileOptionIfs from '../components/admin-mobile-option-ifs';
Vue.component('mobile-option-ifs', adminMobileOptionIfs);

import mobileIndexContactifs from '../components/mobile-index-contactifs';
Vue.component('contactifs', mobileIndexContactifs);

import mobileForm from '../components/mobile-index-form';
Vue.component('mobile-form', mobileForm);

import noteVocal from '../components/noteVocal';
Vue.component('note-vocal', noteVocal);

//////////////////////////////////////////////////////////////////////////////////////////////
import { 
	Menu , 
	Avatar , 
	Layout , 
	Breadcrumb , 
	Col , 
	Row , 
	Button , 
	Table ,
	Modal ,
	Input , 
	Card , 
	Icon , 
	Divider , 
	Form ,
	Radio , 
	Popover , 
	Drawer ,
	Badge ,
	Comment , 
	Tooltip , 
	Spin , 
	List , 
	Alert , 
	Tabs ,
	Select , 
	DatePicker ,
	InputNumber , 
} from 'ant-design-vue';

Vue.component( Menu.name, Menu );
Vue.component( Menu.Item.name, Menu.Item );
Vue.component( Menu.SubMenu.name, Menu.SubMenu );

Vue.component( Avatar.name, Avatar );

Vue.component( Layout.name, Layout );
Vue.component( Layout.Header.name, Layout.Header );
Vue.component( Layout.Content.name, Layout.Content );
Vue.component( Layout.Sider.name, Layout.Sider );

Vue.component( Row.name, Row );
Vue.component( Col.name, Col );

Vue.component( Breadcrumb.name, Breadcrumb );

Vue.component( Button.name, Button );
Vue.component( Button.Group.name, Button.Group );

Vue.component( Table.name, Table );

Vue.component( Modal.name, Modal );

Vue.component( Input.name, Input );
Vue.component( Input.TextArea.name, Input.TextArea );

Vue.component( Tooltip.name, Tooltip );

Vue.component( Card.name, Card );
Vue.component( Card.Meta.name, Card.Meta );

Vue.component( Icon.name, Icon );

Vue.component( Divider.name, Divider );

Vue.component( Form.name, Form );
Vue.component( Form.Item.name, Form.Item );

Vue.component( Radio.name, Radio );
Vue.component( Radio.Button.name, Radio.Button );
Vue.component( Radio.Group.name, Radio.Group );

Vue.component( Popover.name, Popover );

Vue.component( Drawer.name, Drawer );

Vue.component( Badge.name, Badge );

Vue.component( Comment.name, Comment );

Vue.component( Spin.name, Spin );

Vue.component( List.name, List );
Vue.component( List.Item.name, List.Item );
Vue.component( List.Item.Meta.name, List.Item.Meta );

Vue.component( Alert.name, Alert );

Vue.component( Tabs.name, Tabs );
Vue.component( Tabs.TabPane.name, Tabs.TabPane );

Vue.component( Select.name, Select );
Vue.component( Select.Option.name, Select.Option );

Vue.component( DatePicker.name, DatePicker );

Vue.component( InputNumber.name, InputNumber );

let comp = {}

export default comp;