<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-header-menu" class="admin-page">
    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                Header & Menu
                <div class="sub header">
                    Header layout options & menu items
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
            <div class="ui header">Layout</div>

            <div class="three fields">
                <div class="field">
                    <label>Style</label>
                    <select class="ui dropdown" ng-model="settings.style">
                        <option value="left">Left Brand & Right Menu</option>
                        <option value="right">Left Menu & Right Brand</option>
                        <option value="left_sidebar">Left Sidebar</option>
                        <option value="right_sidebar">Right Sidebar</option>
                    </select>
                </div>
                <div class="field">
                    <label>Brand Type</label>
                    <select class="ui dropdown" ng-model="settings.brand_type">
                        <option value="name">Name</option>
                        <option value="logo">Logo</option>
                    </select>
                </div>
                <div class="field" ng-if="settings.brand_type === 'logo'">
                    <label>Logo Height (px)</label>
                    <input type="number" ng-model="settings.logo_height">
                </div>
            </div>

            <div class="inline field" ng-if="settings.style === 'left' || settings.style === 'right'">
                <label>Screen Breakpoint (px):</label>
                <input type="number" placeholder="1024" ng-model="settings.screen_breakpoint">
            </div>

            <div class="four fields">
                <div class="field">
                    <label>Header Background Color</label>
                    <input class="color-input" type="color" ng-model="settings.header_bg_color">
                </div>
                <div class="field">
                    <label>Header Font Color</label>
                    <input class="color-input" type="color" ng-model="settings.header_font_color">
                </div>
                <div class="field">
                    <label>Sidebar Background Color</label>
                    <input class="color-input" type="color" ng-model="settings.sidebar_bg_color">
                </div>
                <div class="field">
                    <label>Sidebar Font Color</label>
                    <input class="color-input" type="color" ng-model="settings.sidebar_font_color">
                </div>
            </div>
        </div>

        <div class="ui raised segment">
            <div class="ui header">Menu</div>
            <admin-menu items="settings.menu"></admin-menu>
        </div>
    </div>
</div>