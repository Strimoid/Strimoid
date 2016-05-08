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
'"><span\n            class="glyphicon glyphicon-eye-open"></span></button>\n    <button class="group_block_btn btn btn-sm ' +
((__t = ( block_class )) == null ? '' : __t) +
'"><span class="glyphicon glyphicon-ban-circle"></span>\n    </button>\n</div>\n';

}
return __p
}})();