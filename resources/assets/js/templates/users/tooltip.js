// User tooltip template - converted from Handlebars to template literal
export default function userTooltipTemplate({ username, observe_class, block_class }) {
    return `<div class="btn-group" data-name="${username}">
    <a href="/conversations/new/${username}" class="btn btn-sm btn-default">
        <i class="fa fa-envelope"></i>
    </a>
    <button class="user_observe_btn btn btn-sm ${observe_class}">
        <i class="fa fa-eye"></i>
    </button>
    <button class="user_block_btn btn btn-sm ${block_class}">
        <i class="fa fa-ban"></i>
    </button>
</div>`;
}
