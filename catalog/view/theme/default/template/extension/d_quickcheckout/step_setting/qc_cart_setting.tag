<qc_cart_setting>
    <!-- Nav Settings -->
    <qc_step_setting 
    if={getState().edit} 
    setting_id={setting_id} 
    step="cart">
        <label 
        class="ve-btn ve-btn--default { (getConfig().cart.display == 1) ? 'active' : ''}" 
        for="cart_display" 
        onclick="{parent.editCheckbox}">
            <input 
            name="config[{getAccount()}][cart][display]" 
            type="hidden" 
            value="0">
            <input 
            name="config[{getAccount()}][cart][display]" 
            type="checkbox" 
            value="1" 
            checked={ (getConfig().cart.display == 1) }>
            <i class="fa fa-eye"></i>
        </label>
    </qc_step_setting>

    <!-- Sidebae Settings -->
    <qc_setting 
    if={getState().edit} 
    setting_id={setting_id}  
    title={ getLanguage().cart.heading_title } >
        <div class="ve-editor__setting__content__section">
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_display}</label>
                <table class="ve-table ve-table--bordered">
                    <thead>
                        <tr>
                            <th>{getLanguage().general.text_guest}</th>
                            <th>{getLanguage().general.text_register}</th>
                            <th>{getLanguage().general.text_logged}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><qc_switcher 
                                onclick="{parent.edit}" 
                                name="config[guest][cart][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.guest.cart.display } 
                            /></td>
                            <td><qc_switcher 
                                onclick="{parent.edit}" 
                                name="config[register][cart][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.register.cart.display } 
                            /></td>
                            <td><qc_switcher 
                                onclick="{parent.edit}" 
                                name="config[logged][cart][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.logged.cart.display } 
                            /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_style}</label>
                <br/>
                <div class="ve-btn-group" data-toggle="buttons">

                    <label class="ve-btn ve-btn--white { getState().config.guest.cart.style == 'clear' ? 'active' : '' }" onclick="{parent.editStyle}" >
                        <input name="config[guest][cart][style]" type="checkbox" value="clear" checked={ (getState().config.guest.cart.style == 'clear') }> 
                        <i class="fa fa-window-minimize"></i>
                    </label>

                    <label class="ve-btn ve-btn--white { getState().config.guest.cart.style == 'card' ? 'active' : '' }" onclick="{parent.editStyle}" >
                        <input name="config[guest][cart][style]" type="checkbox" value="card" checked={ (getState().config.guest.cart.style == 'card') }> 
                        <i class="fa fa-window-maximize"></i>
                    </label>
                    <input type="hidden" name="config[guest][cart][style]" value={getState().config.guest.cart.style}/>
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_title}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[cart][heading_title]" value={ getLanguage().cart.heading_title } />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_description}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[cart][text_description]" value={ getLanguage().cart.text_description } />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().cart.text_apply}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[cart][button_apply]" value={ getLanguage().cart.button_apply } />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_display} { getLanguage().cart.entry_image}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[{getAccount()}][cart][columns][image][display]" 
                        data-label-text="Enabled" 
                        value={ getConfig().cart.columns.image.display } 
                    />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_display} { getLanguage().cart.entry_name}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[{getAccount()}][cart][columns][name][display]" 
                        data-label-text="Enabled" 
                        value={ getConfig().cart.columns.name.display } 
                    />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_display} { getLanguage().cart.entry_model}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[{getAccount()}][cart][columns][model][display]" 
                        data-label-text="Enabled" 
                        value={ getConfig().cart.columns.model.display } 
                    />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_display} { getLanguage().cart.entry_quantity}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[{getAccount()}][cart][columns][quantity][display]" 
                        data-label-text="Enabled" 
                        value={ getConfig().cart.columns.quantity.display } 
                    />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_display} { getLanguage().cart.entry_price}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[{getAccount()}][cart][columns][price][display]" 
                        data-label-text="Enabled" 
                        value={ getConfig().cart.columns.price.display } 
                    />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_display} { getLanguage().cart.entry_total}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[{getAccount()}][cart][columns][total][display]" 
                        data-label-text="Enabled" 
                        value={ getConfig().cart.columns.total.display } 
                    />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_display} { getLanguage().cart.entry_coupon}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[{getAccount()}][cart][option][coupon][display]" 
                        data-label-text="Enabled" 
                        value={ getConfig().cart.option.coupon.display } 
                    />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_display} { getLanguage().cart.entry_voucher}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[{getAccount()}][cart][option][voucher][display]" 
                        data-label-text="Enabled" 
                        value={ getConfig().cart.option.voucher.display } 
                    />
                </div>
            </div>

            <div class="ve-field" if={getConfig().cart.option.reward.display != '' }>
                <label class="ve-label">{getLanguage().general.text_display} { getLanguage().cart.entry_reward}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[{getAccount()}][cart][option][reward][display]" 
                        data-label-text="Enabled" 
                        value={ getConfig().cart.option.reward.display } 
                    />
                </div>
            </div>
        </div>
    </qc_setting>
    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = 'qc_cart_setting';

        var tag = this;

        editCheckbox(e){
            var checkbox = $(e.currentTarget).find('input[type=checkbox]');
            checkbox.prop("checked", !checkbox.prop("checked"));
            this.store.dispatch('setting/edit', $(tag.root).find('.step-setting').serializeJSON());
        }

        editStyle(e){
            var data = $('#'+ tag.setting_id).find('form').serializeJSON();
            data.config.guest.cart.style = $(e.currentTarget).find('input').val();
            $(e.currentTarget).parent().find('input[type="hidden"]').val($(e.currentTarget).find('input').val());
            this.store.dispatch('setting/edit', data);
        }

        edit(e){
            this.store.dispatch('setting/edit', $('#'+tag.setting_id).find('form').serializeJSON());
        }
        
    </script>
</qc_cart_setting>