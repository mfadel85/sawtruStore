<qc_confirm_setting>
    <!-- Nav Settings -->
    <qc_step_setting 
    if={getState().edit} 
    setting_id={setting_id} 
    step="confirm">
        <label class="ve-btn ve-btn--default { (getConfig().confirm.display == 1) ? 'active' : ''}" for="confirm_display" onclick="{parent.editCheckbox}">
            <input 
            name="config[{getAccount()}][confirm][display]" 
            type="hidden"  
            value="0">
            <input 
            name="config[{getAccount()}][confirm][display]" 
            id="confirm_display" 
            type="checkbox" 
            value="1" 
            checked={ (getConfig().confirm.display == 1) }>
            <i class="fa fa-eye"></i>
        </label>
    </qc_step_setting>

    <!-- Sidebar Settings -->
    <qc_setting 
    if={getState().edit} 
    setting_id={setting_id} 
    title={ getLanguage().confirm.heading_title } >
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
                                name="config[guest][confirm][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.guest.confirm.display } 
                            /></td>
                            <td><qc_switcher 
                                onclick="{parent.edit}" 
                                name="config[register][confirm][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.register.confirm.display } 
                            /></td>
                            <td><qc_switcher 
                                onclick="{parent.edit}" 
                                name="config[logged][confirm][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.logged.confirm.display } 
                            /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_style}</label>
                <br/>
                <div class="ve-btn-group" data-toggle="buttons">

                    <label class="ve-btn ve-btn--white { getState().config.guest.confirm.style == 'clear' ? 'active' : '' }" onclick="{parent.editStyle}" >
                        <input name="config[guest][confirm][style]" type="checkbox" value="clear" checked={ (getState().config.guest.confirm.style == 'clear') }> 
                        <i class="fa fa-window-minimize"></i>
                    </label>

                    <label class="ve-btn ve-btn--white { getState().config.guest.confirm.style == 'card' ? 'active' : '' }" onclick="{parent.editStyle}" >
                        <input name="config[guest][confirm][style]" type="checkbox" value="card" checked={ (getState().config.guest.confirm.style == 'card') }> 
                        <i class="fa fa-window-maximize"></i>
                    </label>
                    <input type="hidden" name="config[guest][confirm][style]" value={getState().config.guest.confirm.style}/>
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_title}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[confirm][heading_title]" value={ getLanguage().confirm.heading_title } />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_description}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[confirm][text_description]" value={ getLanguage().confirm.text_description } />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().confirm.text_confirm}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[confirm][button_confirm]" value={ getLanguage().confirm.button_confirm } />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_display} {getLanguage().confirm.entry_button_prev}</label>
                <div>
                    <qc_switcher 
                    onclick="{parent.edit}" 
                    name="config[guest][confirm][buttons][prev][display]" 
                    data-label-text="Enabled" 
                    value={ getState().config.guest.confirm.buttons.prev.display } />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().confirm.entry_button_prev}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[confirm][text_prev]" value={ getLanguage().confirm.text_prev } />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().confirm.entry_button_next}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[confirm][text_next]" value={ getLanguage().confirm.text_next } />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().confirm.text_trigger}</label>
                <input onchange="{parent.edit}" type="text" class="ve-input" name="session[confirm][trigger]" value={ getSession().confirm.trigger } />
            </div>
        </div>
    </qc_setting>
    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = 'qc_confirm_setting';

        var tag = this;

        editCheckbox(e){
            var checkbox = $(e.currentTarget).find('input[type=checkbox]');
            checkbox.prop("checked", !checkbox.prop("checked"));
            this.store.dispatch('setting/edit', $(tag.root).find('.step-setting').serializeJSON());
        }

        editStyle(e){
            var data = $('#'+ tag.setting_id).find('form').serializeJSON();
            data.config.guest.confirm.style = $(e.currentTarget).find('input').val();
            $(e.currentTarget).parent().find('input[type="hidden"]').val($(e.currentTarget).find('input').val());
            this.store.dispatch('setting/edit', data);
        }

        edit(e){
            this.store.dispatch('setting/edit', $('#'+tag.setting_id).find('form').serializeJSON());
        }
    </script>
</qc_confirm_setting>