export default class VoteButtons extends React.Component {
    render() {
        let uvClass = this.props.state == 'uv' ? 'btn-success' : ''
        let dvClass = this.props.state == 'dv' ? 'btn-danger' : ''

        return <div>
            <button type="button" className="btn btn-default btn-sm pull-left vote-btn-up { uvClass }">
                <span className="fa fa-arrow-up vote-up"></span>
                <span className="count">{ this.props.uv}</span>
            </button>

            <button type="button" className="btn btn-default btn-sm pull-left vote-btn-down { dvClass }">
                <span className="fa fa-arrow-down vote-down"></span>
                <span className="count">{ this.props.dv }</span>
            </button>
        </div>;
    }
}
