// Group tooltip template - converted from Handlebars to template literal
export default function groupTooltipTemplate({ groupname, subscribe_class, block_class }) {
    return `<div class="btn-group" data-name="${groupname}">
    <button class="group_subscribe_btn btn btn-sm ${subscribe_class}">
        <i class="fa fa-rss"></i>
    </button>
    <button class="group_block_btn btn btn-sm ${block_class}">
        <i class="fa fa-ban"></i>
    </button>
</div>`;
}
