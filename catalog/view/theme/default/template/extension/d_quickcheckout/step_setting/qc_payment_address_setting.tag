<qc_payment_address_setting>
    <!-- Nav Settings -->
    <qc_step_setting 
    if={getState().edit} 
    setting_id={setting_id}
    onDelete={updateFields}
    step="payment_address">
        <label 
        class="ve-btn ve-btn--default { (getConfig().payment_address.display == 1) ? 'active' : ''}" 
        for="payment_address_display" 
        onclick="{parent.editCheckbox}">
            <input 
            name="config[{getAccount()}][payment_address][display]" 
            type="hidden"  
            value="0">
            <input 
            name="config[{getAccount()}][payment_address][display]" 
            id="payment_address_display" 
            type="checkbox" 
            value="1" 
            checked={ (getConfig().payment_address.display == 1) }>
            <i class="fa fa-eye"></i>
        </label>
    </qc_step_setting>

    <!-- Sidebar Settings -->
    <qc_setting 
    if={getState().edit} 
    setting_id={setting_id}
    title={getLanguage().payment_address.heading_title} >
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
                                name="config[guest][payment_address][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.guest.payment_address.display } 
                            /></td>
                            <td><qc_switcher 
                                onclick="{parent.edit}" 
                                name="config[register][payment_address][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.register.payment_address.display } 
                            /></td>
                            <td><qc_switcher 
                                onclick="{parent.edit}" 
                                name="config[logged][payment_address][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.logged.payment_address.display } 
                            /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_style}</label>
                <br/>
                <div class="ve-btn-group" data-toggle="buttons">

                    <label class="ve-btn ve-btn--white { getState().config.guest.payment_address.style == 'clear' ? 'active' : '' }" onclick="{parent.editStyle}" >
                        <input name="config[guest][payment_address][style]" type="checkbox" value="clear" checked={ (getState().config.guest.payment_address.style == 'clear') }> 
                        <i class="fa fa-window-minimize"></i>
                    </label>

                    <label class="ve-btn ve-btn--white { getState().config.guest.payment_address.style == 'card' ? 'active' : '' }" onclick="{parent.editStyle}" >
                        <input name="config[guest][payment_address][style]" type="checkbox" value="card" checked={ (getState().config.guest.payment_address.style == 'card') }> 
                        <i class="fa fa-window-maximize"></i>
                    </label>
                    <input type="hidden" name="config[guest][payment_address][style]" value={getState().config.guest.payment_address.style}/>
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_title}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[payment_address][heading_title]" value={ getLanguage().payment_address.heading_title } />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_description}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[payment_address][text_description]" value={ getLanguage().payment_address.text_description } />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_logged} {getLanguage().payment_address.text_address_style}</label>
                <br/>
                <div class="ve-btn-group" data-toggle="buttons">
                    <label class="ve-btn ve-btn--white { getState().config.guest.payment_address.address_style == 'radio' ? 'active' : '' }" onclick="{parent.editAddressStyle}" >
                        <input name="config[guest][payment_address][address_style]" type="checkbox" value="radio" checked={ (getState().config.guest.payment_address.address_style == 'radio') }> 
                        {getLanguage().general.entry_radio} 
                    </label>

                    <label class="ve-btn ve-btn--white { getState().config.guest.payment_address.address_style == 'select' ? 'active' : '' }" onclick="{parent.editAddressStyle}" >
                        <input name="config[guest][payment_address][address_style]" type="checkbox" value="select" checked={ (getState().config.guest.payment_address.address_style == 'select') }> 
                        {getLanguage().general.entry_select} 
                    </label>
                    <input type="hidden" name="config[guest][payment_address][address_style]" value={getState().config.guest.payment_address.address_style}/>
                </div>
            </div>
        </div>
    </qc_setting>
    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = 'qc_payment_address_setting';

        var tag = this;

        tag.fields = this.store.getFieldIds('payment_address');

        var initFieldSortable = false;

        updateFields(){
            tag.fields = this.store.getFieldIds('payment_address');
            this.store.render();
        }

        editCheckbox(e){
            var checkbox = $(e.currentTarget).find('input[type=checkbox]');
            checkbox.prop("checked", !checkbox.prop("checked"));
            this.store.dispatch('setting/edit', $(tag.root).find('.step-setting').serializeJSON());
            initFieldSortable = true;
        }

        editAddressStyle(e){
            var data = $('#'+ tag.setting_id).find('form').serializeJSON();
            data.config.guest.payment_address.address_style = $(e.currentTarget).find('input').val();
            $(e.currentTarget).parent().find('input[type="hidden"]').val($(e.currentTarget).find('input').val());
            this.store.dispatch('setting/edit', data);
        }

        editStyle(e){
            var data = $('#'+ tag.setting_id).find('form').serializeJSON();
            data.config.guest.payment_address.style = $(e.currentTarget).find('input').val();
            $(e.currentTarget).parent().find('input[type="hidden"]').val($(e.currentTarget).find('input').val());
            this.store.dispatch('setting/edit', data);
        }

        edit(e){
            this.store.dispatch('setting/edit', $('#'+tag.setting_id).find('form').serializeJSON());
        }

        this.on('updated', function(){
            if(initFieldSortable){
                setTimeout(function(){
                    tag.store.initFieldSortable('payment_address');
                    initFieldSortable = false;
                }, 300);
                
            }
        })
    </script>
</qc_payment_address_setting>