<qc_custom_setting>
    <!-- Nav Settings -->
    <qc_step_setting 
    if={getState().edit} 
    setting_id={setting_id} 
    step="custom">
        <label 
        class="ve-btn ve-btn--default { (getConfig().custom.display == 1) ? 'active' : ''}" 
        for="custom_display" 
        onclick="{parent.editCheckbox}">
            <input 
            name="config[{getAccount()}][custom][display]" 
            type="hidden" 
            value="0">
            <input 
            name="config[{getAccount()}][custom][display]" 
            id="custom_display" 
            type="checkbox" 
            value="1" 
            checked={ (getConfig().custom.display == 1) }>
            <i class="fa fa-eye"></i>
        </label>
    </qc_step_setting>

    <!-- Sidebar Settings -->
    <qc_setting
    if={getState().edit} 
    setting_id={setting_id} 
    title={ getLanguage().custom.heading_title } >
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
                                name="config[guest][custom][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.guest.custom.display } 
                            /></td>
                            <td><qc_switcher 
                                onclick="{parent.edit}" 
                                name="config[register][custom][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.register.custom.display } 
                            /></td>
                            <td><qc_switcher 
                                onclick="{parent.edit}" 
                                name="config[logged][custom][display]" 
                                data-label-text="Enabled" 
                                value={ getState().config.logged.custom.display } 
                            /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_style}</label>
                <br/>
                <div class="ve-btn-group" data-toggle="buttons">

                    <label class="ve-btn ve-btn--white { getState().config.guest.custom.style == 'clear' ? 'active' : '' }" onclick="{parent.editStyle}" >
                        <input name="config[guest][custom][style]" type="checkbox" value="clear" checked={ (getState().config.guest.custom.style == 'clear') }> 
                        <i class="fa fa-window-minimize"></i>
                    </label>

                    <label class="ve-btn ve-btn--white { getState().config.guest.custom.style == 'card' ? 'active' : '' }" onclick="{parent.editStyle}" >
                        <input name="config[guest][custom][style]" type="checkbox" value="card" checked={ (getState().config.guest.custom.style == 'card') }> 
                        <i class="fa fa-window-maximize"></i>
                    </label>
                    <input type="hidden" name="config[guest][custom][style]" value={getState().config.guest.custom.style}/>
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_title}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[custom][heading_title]" value={ getLanguage().custom.heading_title } />
                </div>
            </div>
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_description}</label>
                <div class="ve-input-group">
                    <span class="ve-input-group__addon">
                        <img src="{getLanguage().general.img}">
                    </span>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="language[custom][text_description]" value={ getLanguage().custom.text_description } />
                </div>
            </div>
        </div>
    </qc_setting>
    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = 'qc_custom_setting';

        var tag = this;

        tag.fields = this.store.getFieldIds('custom');

        var initFieldSortable = false;

        addCustomField(){
            tag.fields = this.store.getFieldIds('custom');
            this.store.rerander();
        }

        editCheckbox(e){
            var checkbox = $(e.currentTarget).find('input[type=checkbox]');
            checkbox.prop("checked", !checkbox.prop("checked"));
            this.store.dispatch('setting/edit', $(tag.root).find('.step-setting').serializeJSON());
            initFieldSortable = true;
        }

        editStyle(e){
            var data = $('#'+ tag.setting_id).find('form').serializeJSON();
            data.config.guest.custom.style = $(e.currentTarget).find('input').val();
            $(e.currentTarget).parent().find('input[type="hidden"]').val($(e.currentTarget).find('input').val());
            this.store.dispatch('setting/edit', data);
        }

        edit(e){
            this.store.dispatch('setting/edit', $('#'+tag.setting_id).find('form').serializeJSON());
        }

        this.on('updated', function(){
            if(initFieldSortable){
                setTimeout(function(){
                    tag.store.initFieldSortable('custom');
                    initFieldSortable = false;
                }, 300);
                
            }
        })
    </script>
</qc_custom_setting>