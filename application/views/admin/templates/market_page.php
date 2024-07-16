<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-market-page" class="admin-page">

    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                Market Page
                <div class="sub header">
                    CryptoCurrencies table page options
                </div>
            </div>
        </h1>
        <div class="ui green labeled icon button" ng-click="save()">
            <i class="save icon"></i>
            Save
        </div>
        <a href="{{ urls.market_page }}" target="_blank" class="ui labeled icon button">
            <i class="external square alternative icon"></i>
            Preview
        </a>
    </div>

    <div class="admin-page-content">

        <div class="ui raised segment">
            <div class="ui header">Header</div>

            <div class="field">
                <label>Top HTML</label>
                <textarea placeholder="<div>place here HTML</div>" rows="5" ng-model="settings.header_top_html"></textarea>
            </div>

            <div class="field">
                <label>Title</label>
                <admin-multi-lang-input content="settings.title"></admin-multi-lang-input>
            </div>

            <div class="field">
                <label>Subtitle</label>
                <admin-multi-lang-input content="settings.subtitle"></admin-multi-lang-input>
            </div>

            <div class="field">
                <label>Bottom HTML</label>
                <textarea placeholder="<div>place here HTML</div>" rows="5" ng-model="settings.header_bottom_html"></textarea>
            </div>
        </div>

        <div class="ui raised segment">
            <div class="ui header">Options</div>

            <div class="field">
                <label>Columns</label>
                <admin-search multiple="true" values="market_columns" model="settings.table_columns"></admin-search>
            </div>


            <div class="inline field">
                <label>Max Width (px):</label>
                <input type="number" placeholder="1024" ng-model="settings.table_max_width">
            </div>

            <div class="inline field">
                <label>Items per page</label>
                <input type="number" placeholder="25" ng-model="settings.page_size">
            </div>
        </div>

        <div class="ui raised segment">
            <div class="ui header">After</div>
            <div class="field">
                <label>Custom HTML</label>
                <textarea placeholder="<div>place here HTML</div>" rows="5" ng-model="settings.after_html"></textarea>
            </div>
        </div>

        <admin-custom-seo settings="settings"></admin-custom-seo>

    </div>
</div>