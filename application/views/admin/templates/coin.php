<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-cryptocurrencies" class="admin-page">
    <div class="admin-page-content">

        <div class="ui borderless mini labeled icon menu">
            <div class="active link item" ui-sref="coins">
                <i class="reply icon"></i>
                Coins
            </div>
            <a target="_blank" class="item" ng-href="{{ page_url }}">
                <i class="external icon"></i>
                Preview
            </a>
            <div class="link item" ng-click="save()">
                <i class="save icon"></i>
                Save
            </div>
        </div>

        <div class="ui raised segment">
            <h1 class="ui header">
                <div class="content">
                    {{ coin.name }}
                    <div class="sub header">
                        {{ coin.symbol }}
                    </div>
                </div>
            </h1>

            <div class="field">
                <div class="ui toggle checkbox">
                    <input type="checkbox" ng-model="coin.status" ng-true-value="1" ng-false-value="0">
                    <label>Enabled</label>
                </div>
            </div>
        </div>


        <div ng-if="coin.status === 1">

            <div class="ui raised segment">

                <div class="two fields">
                    <div class="field">
                        <div class="ui header">
                            Info
                        </div>

                        <div class="ui left labeled input">
                            <label class="ui label">Last Update: </label>
                            <input type="text" readonly ng-value="coin.info_updated">
                            <button class="ui teal button" ng-click="updateInfo()">
                                <i class="sync icon"></i>
                                Sync
                            </button>
                        </div>
                    </div>

                    <div class="field">
                        <div class="ui header">
                            Prices
                        </div>

                        <div class="ui left labeled input">
                            <label class="ui label">Last Update: </label>
                            <input type="text" readonly ng-value="coin.prices_updated">
                        </div>
                    </div>
                </div>

            </div>

            <div class="ui raised segment">
                <div class="ui header">
                    Page Content
                </div>

                <admin-multi-lang-textarea content="coin.page_content" rows="10"></admin-multi-lang-textarea>

            </div>

        </div>
    </div>
</div>