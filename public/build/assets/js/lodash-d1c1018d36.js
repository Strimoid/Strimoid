(function() {
window["_"] = window["_"] || {};
window["_"]["tpl"] = window["_"]["tpl"] || {};

window["_"]["tpl"]["entries-reply"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<div class="panel-default entry entry_reply" data-id="' +
__e( hashId ) +
'">\n    <a name="' +
__e( hashId ) +
'"></a>\n    <div class="entry_avatar">\n        <img src="' +
__e( avatarPath ) +
'" alt="' +
__e( user.name ) +
'">\n    </div>\n    <div class="panel-heading entry_header" data-hover="user_widget" data-user="' +
__e( user.name ) +
'">\n        <a href="/u/' +
__e( user.name ) +
'" class="entry_author">' +
((__t = ( user.name )) == null ? '' : __t) +
'</a>\n        <span class="pull-right">\n            <i class="fa fa-clock-o"></i>\n            <a href="/e/' +
__e( hashId ) +
'">\n                <time pubdate title="' +
__e( created_at ) +
'">chwilę temu</time>\n            </a>\n            <span class="voting" data-id="' +
__e( hashId ) +
'" state="none" data-type="entry-reply">\n                <button type="button" class="btn btn-secondary btn-xs vote-btn-up">\n                    <i class="fa fa-arrow-up vote-up"></i>\n                    <span class="count">0</span>\n                </button>\n                <button type="button" class="btn btn-secondary btn-xs vote-btn-down">\n                    <i class="fa fa-arrow-down vote-down"></i>\n                    <span class="count">0</span>\n                </button>\n            </span>\n        </span>\n    </div>\n    <div class="entry_text md">' +
((__t = ( text )) == null ? '' : __t) +
'</div>\n    <div class="entry_actions pull-right">\n        <i class="fa fa-star-o action_link save_entry" title="zapisz"></i>\n        <a class="entry_reply_link action_link">odpowiedz</a>\n        <a href="' +
__e( entryUrl ) +
'">#</a>\n    </div>\n</div>\n';

}
return __p
}})();
(function() {
window["_"] = window["_"] || {};
window["_"]["tpl"] = window["_"]["tpl"] || {};

window["_"]["tpl"]["entries-widget"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<div class="panel-default entry" data-id="' +
__e( hashId ) +
'">\n    <a name="' +
__e( hashId ) +
'"></a>\n    <div class="entry_avatar">\n        <img src="' +
__e( avatarPath ) +
'" alt="' +
__e( user.name ) +
'">\n    </div>\n    <div class="panel-heading entry_header" data-hover="user_widget" data-user="' +
__e( user.name ) +
'">\n        <a href="/u/' +
__e( user.name ) +
'" class="entry_author">' +
((__t = ( user.name )) == null ? '' : __t) +
'</a>\n        <span class="pull-right">\n            <i class="fa fa-tag"></i>\n            <a href="/g/' +
__e( group.urlname ) +
'" class="entry_group" data-hover="group_widget" data-group="' +
__e( group.urlname ) +
'">g/' +
__e( group.urlname ) +
'</a>\n            <i class="fa fa-clock-o"></i>\n            <a href="/e/' +
__e( hashId ) +
'">\n                <time pubdate title="' +
__e( created_at ) +
'">chwilę temu</time>\n            </a>\n            <span class="voting" data-id="' +
__e( hashId ) +
'" state="none" data-type="entry">\n                <button type="button" class="btn btn-secondary btn-xs vote-btn-up">\n                    <i class="fa fa-arrow-up vote-up"></i>\n                    <span class="count">0</span>\n                </button>\n                <button type="button" class="btn btn-secondary btn-xs vote-btn-down">\n                    <i class="fa fa-arrow-down vote-down"></i>\n                    <span class="count">0</span>\n                </button>\n            </span>\n        </span>\n    </div>\n    <div class="entry_text md">' +
((__t = ( text )) == null ? '' : __t) +
'</div>\n    <div class="entry_actions pull-right">\n        <i class="fa fa-star-o action_link save_entry" title="zapisz"></i>\n        <a class="entry_reply_link action_link">odpowiedz</a>\n        <a href="' +
__e( entryUrl ) +
'">#</a>\n    </div>\n</div>\n';

}
return __p
}})();
(function() {
window["_"] = window["_"] || {};
window["_"]["tpl"] = window["_"]["tpl"] || {};

window["_"]["tpl"]["users-tooltip"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<div class="btn-group" data-name="' +
__e( username ) +
'">\n    <a href="/conversations/new/' +
__e( username ) +
'" class="btn btn-sm btn-default">\n        <i class="fa fa-envelope"></i></a>\n    <button class="user_observe_btn btn btn-sm ' +
__e( observe_class ) +
'">\n        <i class="fa fa-eye"></i>\n    </button>\n    <button class="user_block_btn btn btn-sm ' +
__e( block_class ) +
'">\n        <i class="fa fa-ban"></i>\n    </button>\n</div>\n';

}
return __p
}})();
(function() {
window["_"] = window["_"] || {};
window["_"]["tpl"] = window["_"]["tpl"] || {};

window["_"]["tpl"]["groups-tooltip"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<div class="btn-group" data-name="' +
__e( groupname ) +
'">\n    <button class="group_subscribe_btn btn btn-sm ' +
__e( subscribe_class ) +
'">\n        <i class="fa fa-eye"></i>\n    </button>\n    <button class="group_block_btn btn btn-sm ' +
__e( block_class ) +
'">\n        <i class="fa fa-ban"></i>\n    </button>\n</div>\n';

}
return __p
}})();