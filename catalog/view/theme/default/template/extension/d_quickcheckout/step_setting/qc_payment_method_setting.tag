<qc_payment_method_setting>
    <!-- Nav Settings -->
    <qc_step_setting 
    if={getState().edit} 
    setting_id={setting_id}
    step="payment_method" >
        <label 
        class="ve-btn ve-btn--default { (getConfig().payment_method.display == 1) ? 'active' : ''}" 
        for="payment_method_display" 
        onclick="{parent.editCheckbox}">
            <input 
            name="config[{getAccount()}][payment_method][display]" 
            type="hidden" 
            value="0">
            <input 
            name="config[{getAccount()}][payment_method][display]" 
            id="payment_method_display" 
            type="checkbox" 
            value="1" 
            checked={ (getConfig().payment_method.display == 1) }>
            <i class="fa fa-eye"></i>
        </label>
    </qc_step_setting>

    <!-- Sidebar Settings -->
    <qc_setting 
    if={getState().edit} 
    setting_id={setting_id}
    title={ getLanguage().payment_method.heading_title } >
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
                                name="config[guest][payment_method][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.guest.payment_method.display } 
                            /></td>
                            <td><qc_switcher 
                                onclick="{parent.edit}" 
                                name="config[register][payment_method][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.register.payment_method.display } 
                            /></td>
                            <td><qc_switcher 
                                onclick="{parent.edit}" 
                                name="config[logged][payment_method][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.logged.payment_method.display } 
                            /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_style}</label>
                <br/>
                <div class="ve-btn-group" data-toggle="buttons">

                    <label class="ve-btn ve-btn--white { getState().config.guest.payment_method.style == 'clear' ? 'active' : '' }" onclick="{parent.editStyle}" >
                        <input name="config[guest][payment_method][style]" type="checkbox" value="clear" checked={ (getState().config.guest.payment_method.style == 'clear') }> 
                        <i class="fa fa-window-minimize"></i>
                    </label>

                    <label class="ve-btn ve-btn--white { getState().config.guest.payment_method.style == 'card' ? 'active' : '' }" onclick="{parent.editStyle}" >
                        <input name="config[guest][payment_method][style]" type="checkbox" value="card" checked={ (getState().config.guest.payment_method.style == 'card') }> 
                        <i class="fa fa-window-maximize"></i>
                    </label>
                    <input type="hidden" name="config[guest][payment_method][style]" value={getState().config.guest.payment_method.style}/>
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_title}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[payment_method][heading_title]" value={ getLanguage().payment_method.heading_title } />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_description}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[payment_method][text_description]" value={ getLanguage().payment_method.text_description } />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().payment_method.text_display_options}</label>
                <div>
                    <qc_switcher 
                    onclick="{parent.edit}" 
                    name="config[{getAccount()}][payment_method][display_options]" 
                    data-label-text="Enabled" 
                    value={ getConfig().payment_method.display_options } />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().payment_method.text_display_images}</label>
                <div>
                    <qc_switcher 
                    onclick="{parent.edit}" 
                    name="config[{getAccount()}][payment_method][display_images]" 
                    data-label-text="Enabled" 
                    value={ getConfig().payment_method.display_images } />
                </div>
            </div>

            <div class="ve-field" if={ getSession().payment_methods }>
                <label class="ve-label ve-h5">{ getLanguage().payment_method.text_payment_popups }</label>
            </div>

            <div class="ve-field" each={payment_method in getSession().payment_methods } if={payment_method}>
                <label class="ve-label">{ getSession().payment_methods[payment_method.code].title }</label>
                <div>
                    <qc_switcher 
                    onclick="{parent.parent.edit}" 
                    name="config[guest][payment][payment_popups][{payment_method.code}]" 
                    data-label-text="Enabled" 
                    value={ getState().config.guest.payment.payment_popups[payment_method.code] } />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().payment_method.text_input_style}</label>
                <br/>
                <div class="ve-btn-group" data-toggle="buttons">

                    <label class="ve-btn ve-btn--white { getState().config.guest.payment_method.input_style == 'radio' ? 'active' : '' }" onclick="{parent.editInputStyle}" >
                        
                        <input name="config[guest][payment_method][input_style]" type="checkbox" value="radio" checked={ (getState().config.guest.payment_method.input_style == 'radio') }> 
                        {getLanguage().general.entry_radio} 
                    </label>

                    <label class="ve-btn ve-btn--white { getState().config.guest.payment_method.input_style == 'select' ? 'active' : '' }" onclick="{parent.editInputStyle}" >
                        <input name="config[guest][payment_method][input_style]" type="checkbox" value="select" checked={ (getState().config.guest.payment_method.input_style == 'select') }> 
                        {getLanguage().general.entry_select} 
                    </label>
                    <input type="hidden" name="config[guest][payment_method][input_style]" value={getState().config.guest.payment_method.input_style}/>
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_default } { getLanguage().payment_method.heading_title}</label>
                <select
                    name="config[guest][payment_method][default_option]"
                    class="ve-input"
                    no-reorder
                    onchange="{parent.edit}" >
                    <option
                        each={payment_method in getSession().payment_methods }
                        if={payment_method}
                        value={ payment_method.code }
                        selected={ getState().config.guest.payment_method.default_option == payment_method.code} >
                        { payment_method.title } 
                    </option>
                </select>
            </div>
        </div>
    </qc_setting>
    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = 'qc_payment_method_setting';

        var tag = this;

        editCheckbox(e){
            var checkbox = $(e.currentTarget).find('input[type=checkbox]');
            checkbox.prop("checked", !checkbox.prop("checked"));
            this.store.dispatch('setting/edit', $(tag.root).find('.step-setting').serializeJSON());
        }

        editInputStyle(e){
            var data = $('#'+ tag.setting_id).find('form').serializeJSON();
            data.config.guest.payment_method.input_style = $(e.currentTarget).find('input').val();
            $(e.currentTarget).parent().find('input[type="hidden"]').val($(e.currentTarget).find('input').val());
            this.store.dispatch('setting/edit', data);
        }

        editStyle(e){
            var data = $('#'+ tag.setting_id).find('form').serializeJSON();
            data.config.guest.payment_method.style = $(e.currentTarget).find('input').val();
            $(e.currentTarget).parent().find('input[type="hidden"]').val($(e.currentTarget).find('input').val());
            this.store.dispatch('setting/edit', data);
        }

        edit(e){
            this.store.dispatch('setting/edit', $('#'+tag.setting_id).find('form').serializeJSON());
        }
    </script>
</qc_payment_method_setting>