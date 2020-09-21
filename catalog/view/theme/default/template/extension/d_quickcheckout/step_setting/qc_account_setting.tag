<qc_account_setting>
    <!-- Nav Settings -->
    <qc_step_setting 
    if={getState().edit}
    setting_id={setting_id} 
    step="account">
        <label 
        class="ve-btn ve-btn--default { (getConfig().account.display == 1) ? 'active' : ''}" 
        for="account_display" 
        onclick={parent.editCheckbox}>
            <input 
            name="config[{getAccount()}][account][display]" 
            type="hidden" 
            value="0">
            <input 
            name="config[{getAccount()}][account][display]"
            type="checkbox" 
            value="1" 
            checked={ (getConfig().account.display == 1) }>
            <i class="fa fa-eye"></i>
        </label>
    </qc_step_setting>

    <!-- Sidebae Settings -->
    <qc_setting 
    if={getState().edit}
    setting_id={setting_id} 
    title={ getLanguage().account.heading_title } >
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
                                name="config[guest][account][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.guest.account.display } 
                            /></td>
                            <td><qc_switcher 
                                onclick="{parent.edit}" 
                                name="config[register][account][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.register.account.display } 
                            /></td>
                            <td><qc_switcher 
                                onclick="{parent.edit}" 
                                name="config[logged][account][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.logged.account.display } 
                            /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().account.text_popup}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[guest][account][login_popup]" 
                        data-label-text="Enabled" 
                        value={ getState().config.guest.account.login_popup } 
                    />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_style}</label>
                <br/>
                <div class="ve-btn-group" data-toggle="buttons">

                    <label class="ve-btn ve-btn--white { getState().config.guest.account.style == 'clear' ? 'active' : '' }" onclick="{parent.editStyle}" >
                        <input name="config[guest][account][style]" type="checkbox" value="clear" checked={ (getState().config.guest.account.style == 'clear') }> 
                        <i class="fa fa-window-minimize"></i>
                    </label>

                    <label class="ve-btn ve-btn--white { getState().config.guest.account.style == 'card' ? 'active' : '' }" onclick="{parent.editStyle}" >
                        <input name="config[guest][account][style]" type="checkbox" value="card" checked={ (getState().config.guest.account.style == 'card') }> 
                        <i class="fa fa-window-maximize"></i>
                    </label>
                    <input type="hidden" name="config[guest][account][style]" value={getState().config.guest.account.style}/>
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_title}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[account][heading_title]" value={ getLanguage().account.heading_title } />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_description}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[account][text_description]" value={ getLanguage().account.text_description } />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().account.entry_guest} {getLanguage().general.text_display}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[guest][account][option][guest][display]" 
                        data-label-text="Enabled" 
                        value={ getState().config.guest.account.option.guest.display } 
                    />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().account.entry_register} {getLanguage().general.text_display}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[guest][account][option][register][display]" 
                        data-label-text="Enabled" 
                        value={ getState().config.guest.account.option.register.display } 
                    />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().account.entry_login} {getLanguage().general.text_display}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[guest][account][option][login][display]" 
                        data-label-text="Enabled" 
                        value={ getState().config.guest.account.option.login.display } 
                    />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().account.text_social_login}</label>
                <div>
                    <qc_switcher 
                        onclick="{parent.edit}" 
                        name="config[guest][account][social_login][display]" 
                        data-label-text="Enabled" 
                        value={ getState().config.guest.account.social_login.display } 
                    />
                </div>
            </div>
        </div>
    </qc_setting>

    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = 'qc_account_setting';

        var tag = this;

        editCheckbox(e){
            var checkbox = $(e.currentTarget).find('input[type=checkbox]');
            checkbox.prop("checked", !checkbox.prop("checked"));
            this.store.dispatch('setting/edit', $(tag.root).find('.step-setting').serializeJSON());
        }

        editStyle(e){
            var data = $('#'+ tag.setting_id).find('form').serializeJSON();
            data.config.guest.account.style = $(e.currentTarget).find('input').val();
            $(e.currentTarget).parent().find('input[type="hidden"]').val($(e.currentTarget).find('input').val());
            this.store.dispatch('setting/edit', data);
        }

        edit(e){
            this.store.dispatch('setting/edit', $('#'+tag.setting_id).find('form').serializeJSON());
        }
    </script>

</qc_account_setting>