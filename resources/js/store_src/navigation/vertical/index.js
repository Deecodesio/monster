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
import dashboard from './store/dashboard'
import addonslist from './store/addonslist'
import cuisines from './store/cuisines'
import menu from './store/menu'
import product from './store/product'
import promocodes from './store/promocodes'
import reports from './store/reports'
import restaurant from './store/restaurant'
import restauranttiming from './store/restauranttiming'
import rider_map from './store/rider_map'
import tables from './store/tables'
import subcategory from './store/subcategory'
import appsAndPages from './apps-and-pages'
// import plan from './store/plan'




// Array of sections
export default [
  ...dashboard,
  // ...plan,
  ...appsAndPages,
  ...restaurant,
  ...addonslist,
  ...menu,
  ...subcategory,
  ...product,
  ...restauranttiming,
  ...rider_map,
  ...tables,
  ...promocodes,
  ...cuisines,
  ...reports]

// alert(localStorage.getItem('privilege'))