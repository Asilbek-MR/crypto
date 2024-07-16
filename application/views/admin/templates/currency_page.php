<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-currency-page" class="admin-page">
    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                Currency Page
                <div class="sub header">
                    Individual cryptocurrency page
                </div>
            </div>
        </h1>
        <div class="ui green labeled icon button" ng-click="save()">
            <i class="save icon"></i>
            Save
        </div>
    </div>
    <div class="admin-page-content">

        <div class="ui raised segment">
            <div class="ui header">Header</div>

            <div class="field">
                <label>Top HTML</label>
                <textarea placeholder="<div>place here HTML</div>" rows="5" ng-model="settings.header_top_html"></textarea>
            </div>
            <div class="field">
                <label>Bottom HTML</label>
                <textarea placeholder="<div>place here HTML</div>" rows="5" ng-model="settings.header_bottom_html"></textarea>
            </div>
        </div>

        <div class="ui raised segment">
            <div class="ui header">Options</div>

            <div class="three fields">
                <div class="field">
                    <div class="ui toggle checkbox">
                        <input type="checkbox" ng-model="settings.show_info">
                        <label>Market Info</label>
                    </div>
                </div>
                <div class="field">
                    <div class="ui toggle checkbox">
                        <input type="checkbox" ng-model="settings.show_links">
                        <label>Links</label>
                    </div>
                </div>
                <div class="field">
                    <div class="ui toggle checkbox">
                        <input type="checkbox" ng-model="settings.show_converter">
                        <label>Converter</label>
                    </div>
                </div>
            </div>
            <div class="three fields">
                <div class="field">
                    <div class="ui toggle checkbox">
                        <input type="checkbox" ng-model="settings.show_description">
                        <label>Description</label>
                    </div>
                </div>
                <div class="field">
                    <div class="ui toggle checkbox">
                        <input type="checkbox" ng-model="settings.show_content">
                        <label>Custom Content</label>
                    </div>
                </div>
            </div>
            <div class="ui small header">Tickers</div>
            <div class="inline fields">
                <div class="field">
                    <div class="ui toggle checkbox">
                        <input type="checkbox" ng-model="settings.show_tickers">
                        <label>Show</label>
                    </div>
                </div>
                <div class="field" ng-if="settings.show_tickers">
                    <label>Size:</label>
                    <input type="number" step="1" min="1" max="100" ng-model="settings.tickers_size">
                </div>
            </div>
            <div class="ui small header">Chart</div>
            <div class="field">
                <div class="ui toggle checkbox">
                    <input type="checkbox" ng-model="settings.show_chart">
                    <label>Show</label>
                </div>
            </div>
            <div class="inline fields" ng-if="settings.show_chart">
                <div class="field">
                    <label>Price Color:</label>
                    <input class="color-input" type="color" ng-model="settings.price_color">
                </div>
                <div class="field">
                    <label>Market Cap Color:</label>
                    <input class="color-input" type="color" ng-model="settings.market_cap_color">
                </div>
                <div class="field">
                    <label>Volume Color:</label>
                    <input class="color-input" type="color" ng-model="settings.volume_color">
                </div>
            </div>

        </div>


        <div class="ui raised segment">
            <div class="ui header">After</div>
            <div class="field">
                <label>Custom HTML</label>
                <textarea placeholder="<div>place here HTML</div>" rows="5" ng-model="settings.after_html"></textarea>
            </div>
        </div>

    </div>
</div>