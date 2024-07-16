<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-custom-asset" class="admin-page">
    <div class="admin-page-content">

        <div class="ui borderless mini labeled icon menu">
            <div class="active link item" ui-sref="coins">
                <i class="reply icon"></i>
                Coins
            </div>

            <a target="_blank" class="item" ng-href="{{ page_url }}" ng-if="asset.id">
                <i class="external icon"></i>
                Preview
            </a>

            <div class="link item" ng-click="save()">
                <i class="save icon"></i>
                Save
            </div>

            <div class="right menu">
                <div class="link item" ng-click="remove()" ng-if="asset.id">
                    <i class="trash icon"></i>
                    Remove
                </div>
            </div>
        </div>

        <div class="ui raised segment">
            <div class="three fields">
                <div class="field" ng-class="{error: errors.slug}">
                    <label>ID (letters & hyphen only)</label>
                    <input type="text" placeholder="coin-id" ng-model="asset.slug">
                </div>
                <div class="field" ng-class="{error: errors.symbol}">
                    <label>Symbol</label>
                    <input type="text" placeholder="COIN" ng-model="asset.symbol">
                </div>
                <div class="field" ng-class="{error: errors.name}">
                    <label>Name</label>
                    <input type="text" placeholder="Your Coin" ng-model="asset.name">
                </div>
            </div>
            <div class="two fields" ng-class="{error: errors.circulating_supply}">
                <div class="field">
                    <label>Circulating Supply</label>
                    <input type="number" placeholder="16000000" ng-model="asset.circulating_supply">
                </div>
                <div class="field">
                    <label>Total Supply (Max)</label>
                    <input type="number" placeholder="21000000" ng-model="asset.total_supply">
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>Volume 24h (USD)</label>
                    <input type="number" placeholder="21000000" ng-model="asset.volume_24h_usd">
                </div>
            </div>

            <div class="field" ng-class="{error: errors.image_thumb}">
                <admin-image image="asset.image_thumb" title="Image Thumb (25x25)"></admin-image>
            </div>
            <div class="field" ng-class="{error: errors.image_small}">
                <admin-image image="asset.image_small" title="Image Small (50x50)"></admin-image>
            </div>
            <div class="field" ng-class="{error: errors.image_large}">
                <admin-image image="asset.image_large" title="Image Large (250x250)"></admin-image>
            </div>


            <div class="ui message">
                <div class="header">Tracking Price</div>
                <br>
                <div class="content">
                    <ul class="ui list">
                        <li>Custom assets need to track any cryptocurrency.</li>
                        <li>Price will be a multiple of the selected cryptocurrency.</li>
                        <li>You can use Tether (USDT) for less volatile assets ( 1 USDT ~ 1 USD).</li>
                    </ul>
                </div>
            </div>
            <div class="two fields">
                <div class="field" ng-class="{error: errors.tracking_multiple}">
                    <label>Multiple</label>
                    <div class="ui input">
                        <input type="number" placeholder="0.01" ng-model="asset.tracking_multiple">
                    </div>
                </div>
                <div class="field" ng-class="{error: errors.tracking_slug}">
                    <label>CryptoCurrency</label>
                    <admin-search values="coins" model="asset.tracking_slug"></admin-search>
                </div>
            </div>

            <div class="field">
                <label>Custom Content</label>
                <admin-multi-lang-textarea rows="5" content="asset.page_content"></admin-multi-lang-textarea>
            </div>
        </div>
    </div>
</div>