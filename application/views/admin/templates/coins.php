<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-cryptocurrencies" class="admin-page">
    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                Coins
                <div class="sub header">
                    All cryptocurrencies listing & management
                </div>
            </div>
        </h1>
    </div>
    <div class="admin-page-content">

        <div class="ui raised segment">
            <div class="ui right floated labeled teal icon button" ng-click="updateAll()">
                <i class="download icon"></i>
                Refresh
            </div>

            <div class="ui header">
                CryptoCurrencies
            </div>


            <table class="ui unstackable striped celled table" style="width: 100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Symbol</th>
                    <th>Info Updated</th>
                    <th>Prices Updated</th>
                    <th>Status</th>
                </tr>
                </thead>
            </table>
        </div>

        <div class="ui raised segment">
            <div class="ui header">Custom Assets</div>
            <div class="ui teal labeled icon button" ui-sref="custom_asset({id: 'new'})">
                <i class="plus icon"></i>
                Create
            </div>

            <div class="ui selection very relaxed middle aligned divided large list" ng-if="custom_assets.length">
                <div class="item" ng-repeat="asset in custom_assets">
                    <div class="right floated content">
                        <div class="ui basic icon buttons">
                            <a target="_blank" class="ui button" ng-href="{{ getURL($index) }}">
                                <i class="external icon"></i>
                            </a>
                            <div class="ui button" ui-sref="custom_asset({id: asset.id})">
                                <i class="pencil alternative icon"></i>
                            </div>
                            <div class="ui button" ng-click="remove($index)">
                                <i class="trash icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <h3 class="ui header">
                            <div class="content">
                                {{ asset.name }}
                                <div class="sub header">{{ asset.symbol }}</div>
                            </div>
                        </h3>
                    </div>

                </div>
            </div>

            <div class="ui message" ng-if="!custom_assets.length">
                <div class="content">
                    No custom assets where found.
                </div>
            </div>

        </div>

    </div>
</div>