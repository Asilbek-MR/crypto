<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-footer" class="admin-page">
    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                Footer
                <div class="sub header">
                    Footer & bottom bar options
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
            <div class="ui header">Footer</div>

            <div class="field">
                <label>Logo</label>
                <div class="ui toggle checkbox">
                    <input type="checkbox" ng-model="settings.logo">
                    <label>Show</label>
                </div>
            </div>

            <div class="four fields">
                <div class="field">
                    <label>Background Color</label>
                    <input class="color-input" type="color" ng-model="settings.bg_color">
                </div>
                <div class="field">
                    <label>Heading Color</label>
                    <input class="color-input" type="color" ng-model="settings.heading_color">
                </div>
                <div class="field">
                    <label>Link Color</label>
                    <input class="color-input" type="color" ng-model="settings.link_color">
                </div>
                <div class="field">
                    <label>Text Color</label>
                    <input class="color-input" type="color" ng-model="settings.text_color">
                </div>
            </div>

            <div class="field">
                <label>Credits</label>
                <div class="ui toggle checkbox">
                    <input type="checkbox" ng-model="settings.show_credits">
                    <label>Show</label>
                </div>
            </div>
        </div>

        <div class="ui raised segment">
            <div class="ui header">Menu #1</div>
            <div class="inline field">
                <label>Heading:</label>
                <input type="text" placeholder="Menu #1 Heading" ng-model="settings.menu1_heading">
            </div>
            <admin-menu items="settings.menu1"></admin-menu>
        </div>

        <div class="ui raised segment">
            <div class="ui header">Menu #2</div>
            <div class="inline field">
                <label>Heading:</label>
                <input type="text" placeholder="Menu #2 Heading" ng-model="settings.menu2_heading">
            </div>
            <admin-menu items="settings.menu2"></admin-menu>
        </div>

        <div class="ui raised segment">
            <div class="ui header">Menu #3</div>
            <div class="inline field">
                <label>Heading:</label>
                <input type="text" placeholder="Menu #3 Heading" ng-model="settings.menu3_heading">
            </div>
            <admin-menu items="settings.menu3"></admin-menu>
        </div>

        <div class="ui raised segment">
            <div class="ui header">Bottom bar</div>
            <div class="two fields">
                <div class="inline field">
                    <label>Background Color</label>
                    <input class="color-input" type="color" ng-model="settings.bottom_bar_bg_color">
                </div>
                <div class="inline field">
                    <label>Text Color</label>
                    <input class="color-input" type="color" ng-model="settings.bottom_bar_text_color">
                </div>
            </div>

            <div class="field">
                <label>Text</label>
                <input type="text" placeholder="2018 Website" ng-model="settings.bottom_bar_text">
            </div>

        </div>

    </div>

</div>