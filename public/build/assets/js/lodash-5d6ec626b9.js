(function() {
window["_"] = window["_"] || {};
window["_"]["tpl"] = window["_"]["tpl"] || {};

window["_"]["tpl"]["groups-tooltip"] = function(obj) {
obj || (obj = {});
var __t, __p = '';
with (obj) {
__p += '<div class="btn-group" data-name="' +
((__t = ( groupname )) == null ? '' : __t) +
'">\n    <button class="group_subscribe_btn btn btn-sm ' +
((__t = ( subscribe_class )) == null ? '' : __t) +
'">\n        <i class="fa fa-eye"></i>\n    </button>\n    <button class="group_block_btn btn btn-sm ' +
((__t = ( block_class )) == null ? '' : __t) +
'">\n        <i class="fa fa-ban"></i>\n    </button>\n</div>\n';

}
return __p
}})();
(function() {
window["_"] = window["_"] || {};
window["_"]["tpl"] = window["_"]["tpl"] || {};

window["_"]["tpl"]["users-tooltip"] = function(obj) {
obj || (obj = {});
var __t, __p = '';
with (obj) {
__p += '<div class="btn-group" data-name="' +
((__t = ( username )) == null ? '' : __t) +
'">\n    <a href="/conversations/new/' +
((__t = ( username )) == null ? '' : __t) +
'" class="btn btn-sm btn-default">\n        <i class="fa fa-envelope"></i></a>\n    <button class="user_observe_btn btn btn-sm ' +
((__t = ( observe_class )) == null ? '' : __t) +
'">\n        <i class="fa fa-eye"></i>\n    </button>\n    <button class="user_block_btn btn btn-sm ' +
((__t = ( block_class )) == null ? '' : __t) +
'">\n        <i class="fa fa-ban"></i>\n    </button>\n</div>\n';

}
return __p
}})();