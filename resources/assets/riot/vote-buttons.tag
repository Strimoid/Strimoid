<vote-buttons>
    <button type="button" class="btn btn-default btn-{ this.size } pull-left vote-btn-up { this.uvClass }">
        <span class="fa fa-arrow-up vote-up"></span>
        <span class="count">{ opts.uv }</span>
    </button>

    <button type="button" class="btn btn-default btn-{ this.size } pull-left vote-btn-down { this.dvClass }">
        <span class="fa fa-arrow-down vote-down"></span>
        <span class="count">{ opts.dv }</span>
    </button>

    <script>
        this.size = opts["data-size"] || 'sm';
        this.state = opts["data-state"] || '';

        this.uvClass = '';
        this.dvClass = '';

        if (this.state == 'uv') {
            this.uvClass = 'btn-success';
        } else if (this.state == 'dv') {
            this.dvClass = 'btn-danger';
        }
    </script>
</vote-buttons>
