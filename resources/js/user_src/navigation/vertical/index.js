/*

Array of object

Top level object can be:
1. Header
2. Group (Group can have navItems as children)
3. navItem

* Supported Options

/--- Header ---/

header

/--- nav Grp ---/

title
icon (if it's on top level)
tag
tagVariant
children

/--- nav Item ---/

icon (if it's on top level)
title
route: [route_obj/route_name] (I have to resolve name somehow from the route obj)
tag
tagVariant

*/
import dashboard from './user/dashboard'
import appsAndPages from './apps-and-pages'

import chartsAndMaps from './charts-and-maps'
import uiElements from './ui-elements'
import formAndTable from './forms-and-table'
import restaurants from './user/restaurants'
import city from './user/city'
import vehicle from './user/vehicle'
import brands from './user/brands'
import driver from './user/driver'
import document from './user/document'
import cancellation from './user/cancellation'
import promocodes from './user/promocodes'
import restaurantbanner  from './user/restaurantbanner'
import usermanagement from './user/usermanagement'
import cuisines from './user/cuisines'
import addonslist from './user/addonslist'
import payout from './user/payout'
import foodquantity from './user/foodquantity'
import category from './user/category'
import contents from './user/contents'
import settings from './user/settings'
import roles from './user/roles'
import reports from './user/reports'
import language from './user/language'


// Array of sections
export default [...dashboard,
     ...appsAndPages,...restaurants,...city,...vehicle,...brands,...driver,...document,...cancellation,...promocodes,...restaurantbanner,...usermanagement,...cuisines,...addonslist,...payout,...foodquantity,...category,...contents,...settings,...roles,...reports]

// alert(localStorage.getItem('privilege'))