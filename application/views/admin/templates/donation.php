<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-donation" class="admin-page">
    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                Donation
                <div class="sub header">
                    Donation box settings
                </div>
            </div>
        </h1>
        <div class="ui labeled icon green button" ng-click="save()">
            <i class="save icon"></i>
            Save
        </div>
    </div>
    <div class="admin-page-content">

        <div class="ui raised segment">
            <div class="ui header">Donation Window</div>

            <div class="field">
                <label>Title</label>
                <admin-multi-lang-input content="settings.window_title"></admin-multi-lang-input>
            </div>

            <div class="field">
                <label>Content</label>
                <admin-multi-lang-textarea rows="3" content="settings.window_content"></admin-multi-lang-textarea>
            </div>
        </div>

        <div class="ui raised segment">
            <div class="ui header">Paypal</div>
            <div class="ui message">
                <div class="content">
                    <p>Please create a <a target="_blank" href="https://www.paypal.me/">Paypal.me</a> account.</p>
                </div>
            </div>
            <div class="field">
                <div class="ui toggle checkbox">
                    <input type="checkbox" ng-model="settings.paypal_enabled">
                    <label>Enabled</label>
                </div>
            </div>
            <div class="two fields" ng-if="settings.paypal_enabled">
                <div class="field">
                    <label>Username</label>
                    <input type="text" placeholder="JonSnow" ng-model="settings.paypal_user">
                </div>
            </div>
        </div>

        <div class="ui raised segment">
            <div class="ui header">CryptoCurrencies</div>

            <h4 class="ui header">New Address</h4>
            <div class="two fields">
                <div class="field">
                    <label>Name</label>
                    <input type="text" placeholder="Ethereum" ng-model="new_address.name">
                </div>
                <div class="field">
                    <label>Address</label>
                    <input type="text" placeholder="0x123456789" ng-model="new_address.address">
                </div>
            </div>
            <div class="field">
                <button class="tiny ui blue right labeled icon button" ng-click="addAddress()">
                    <i class="plus icon"></i>
                    Add
                </button>
            </div>

            <h4 class="ui header">Addresses</h4>

            <div class="ui selection list" ng-if="settings.addresses.length > 0">
                <div class="item" ng-repeat="entry in settings.addresses">
                    <div class="right floated content">
                        <div class="ui tiny icon basic button" ng-click="removeAddress($index)">
                            <i class="trash icon"></i>
                        </div>
                    </div>
                    <i class="key icon"></i>
                    <div class="middle aligned content">
                        <div class="header">{{ entry.name }}</div>
                        <div class="description">{{ entry.address }}</div>
                    </div>

                </div>
            </div>
            <div class="ui message" ng-if="!settings.addresses.length">
                <div class="content">
                    No addresses were found
                </div>
            </div>

        </div>

    </div>

</div>