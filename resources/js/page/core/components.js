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

let comp = {}

export default comp;