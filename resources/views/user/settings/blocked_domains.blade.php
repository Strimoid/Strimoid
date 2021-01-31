<table class="table" ng-init='blockedDomains = {!! json_encode(Auth::user()->_blocked_domains) !!}'>
    <thead>
    <tr>
        <th>#</th>
        <th>Nazwa domeny</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr ng-repeat="domain in blockedDomains">
        <td>@{!! $index + 1 !!}</td>
        <td>@{!! domain !!}</td>
        <td><button class="btn btn-xs btn-danger" ng-click="unblockDomain(domain)">Usuń</button></td>
    </tr>
    </tbody>
</table>

<form role="form">
    <div class="form-group">
        <label for="domain">Domena</label>
        <input type="text" class="form-control" id="domain" placeholder="np. strims.pl" ng-model="domain">
    </div>
    <button class="btn btn-secondary" ng-click="blockDomain(domain)">Zablokuj domenę</button>
</form>
