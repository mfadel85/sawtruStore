<qc_datetime_setting>
    <qc_field_setting 
        if={getState().edit}
        setting_id={setting_id} 
        field_id={ opts.field_id } 
        step={ opts.step } 
        display={ opts.field.display } 
        require={ opts.field.require }
        delete={opts.field.custom}
        ondelete={onDelete}>

        <label if={(isEmpty(parent.opts.field.depends))} class="ve-btn ve-btn--default { opts.require == 1 ? 'active' : '' }" onclick="{editCheckbox}" >
            <input name="config[{getAccount()}][{opts.step}][fields][{ opts.field_id }][require]" type="hidden"  value="0">
            <input name="config[{getAccount()}][{opts.step}][fields][{ opts.field_id }][require]" type="checkbox" value="1" checked={ (opts.require == 1) }> 
            <i class="fa fa-asterisk"></i>
        </label>

    </qc_field_setting>

    <qc_setting 
        if={getState().edit}
        setting_id={ setting_id }
        title={ opts.field_id } 
        field={ opts.field }
        field_id={ opts.field_id }
        step={ opts.step } >
        <ul class="qc-setting-tabs ve-tab ve-tab--block">
            <li class="qc-tab ve-tab__item active">
                <a href="#{ opts.setting_id }_general" id="{ opts.setting_id }_home_tab" role="tab" data-toggle="tab" aria-controls="{ setting_id }_general" aria-expanded="true">{getLanguage().general.text_general}</a>
            </li>
            <li class="qc-tab ve-tab__item">
                <a href="#{ opts.setting_id }_error" id="{ opts.setting_id }_error_tab" role="tab" data-toggle="tab" aria-controls="{ opts.setting_id }_error" aria-expanded="true">{getLanguage().general.text_error}</a>
            </li>
            <li class="qc-tab ve-tab__item">
                <a href="#{ opts.setting_id }_advanced" id="{ opts.setting_id }_advanced_tab" role="tab" data-toggle="tab" aria-controls="{ opts.setting_id }_advanced" aria-expanded="true">{getLanguage().general.text_dependency}</a>
            </li>
            <!--  <li class="ve-tab__item">
                <a href="#{ opts.setting_id }_design" id="{ opts.setting_id }_design_tab" role="tab" data-toggle="tab" aria-controls="{ opts.setting_id }_design" aria-expanded="true">{getLanguage().general.text_design}</a>
            </li>  -->
        </ul>

        <div class="qc-setting-tab-content"> 
            <div class="qc-setting-tab-pane fade in active" role="tabpanel" id="{ opts.setting_id }_general" aria-labelledby="{ opts.setting_id }_general_tab"> 
                <div class="ve-editor__setting__content__section">
                    <div class="ve-alert ve-alert--warning" if={!isEmpty(parent.opts.field.depends)}>{getLanguage().general.text_has_dependencies}</div>
                    <div class="ve-field">
                        <label class="ve-label">{getLanguage().general.text_display}</label>
                        <div>
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
                                        <td>
                                        <qc_switcher 
                                            if={!isEmpty(getState().config.guest[opts.step].fields[opts.field_id].display)}
                                            onclick="{parent.edit}" 
                                            name="config[guest][{opts.step}][fields][{ opts.field_id }][display]" 
                                            data-label-text="Enabled" 
                                            value={ getState().config.guest[opts.step].fields[opts.field_id].display } 
                                        /></td>
                                        <td><qc_switcher 
                                            if={!isEmpty(getState().config.register[opts.step].fields[opts.field_id].display)}
                                            onclick="{parent.edit}" 
                                            name="config[register][{opts.step}][fields][{ opts.field_id }][display]" 
                                            data-label-text="Enabled" 
                                            value={ getState().config.register[opts.step].fields[opts.field_id].display } 
                                        /></td>
                                        <td><qc_switcher 
                                            if={!isEmpty(getState().config.logged[opts.step].fields[opts.field_id].display)}
                                            onclick="{parent.edit}" 
                                            name="config[logged][{opts.step}][fields][{ opts.field_id }][display]" 
                                            data-label-text="Enabled" 
                                            value={ getState().config.logged[opts.step].fields[opts.field_id].display } 
                                        /></td>
                                    </tr>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="ve-field">
                        <label class="ve-label">{getLanguage().general.text_require}</label>
                        <div>
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
                                            if={!isEmpty(getState().config.guest[opts.step].fields[opts.field_id].require)}
                                            onclick="{parent.edit}" 
                                            name="config[guest][{opts.step}][fields][{ opts.field_id }][require]" 
                                            data-label-text="Enabled" 
                                            value={ getState().config.guest[opts.step].fields[opts.field_id].require } 
                                        /></td>
                                        <td><qc_switcher 
                                            if={!isEmpty(getState().config.register[opts.step].fields[opts.field_id].require)}
                                            onclick="{parent.edit}" 
                                            name="config[register][{opts.step}][fields][{ opts.field_id }][require]" 
                                            data-label-text="Enabled" 
                                            value={ getState().config.register[opts.step].fields[opts.field_id].require } 
                                        /></td>
                                        <td><qc_switcher 
                                            if={!isEmpty(getState().config.logged[opts.step].fields[opts.field_id].require)}
                                            onclick="{parent.edit}" 
                                            name="config[logged][{opts.step}][fields][{ opts.field_id }][require]" 
                                            data-label-text="Enabled" 
                                            value={ getState().config.logged[opts.step].fields[opts.field_id].require } 
                                        /></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="ve-field">
                        <label class="ve-label">{getLanguage().general.text_style}</label>
                        <br/>
                        <div class="ve-btn-group" data-toggle="buttons">

                            <label class="ve-btn ve-btn--white { getState().config.guest[opts.step].fields[opts.field_id].style == 'row' ? 'active' : '' }" onclick="{parent.editStyle}" >
                                <input name="config[guest][{opts.step}][fields][{ opts.field_id }][style]" type="checkbox" value="row" checked={ (getState().config.guest[opts.step].fields[opts.field_id].style == 'row') }> 
                                <i class="fa fa-minus"></i>
                            </label>

                            <label class="ve-btn ve-btn--white { getState().config.guest[opts.step].fields[opts.field_id].style == 'col' ? 'active' : '' }" onclick="{parent.editStyle}" >
                                <input name="config[guest][{opts.step}][fields][{ opts.field_id }][style]" type="checkbox" value="col" checked={ (getState().config.guest[opts.step].fields[opts.field_id].style == 'col') }> 
                                <i class="fa fa-columns"></i>
                            </label>

                            <label class="ve-btn ve-btn--white { getState().config.guest[opts.step].fields[opts.field_id].style == 'list' ? 'active' : '' }" onclick="{parent.editStyle}" >
                                <input name="config[guest][{opts.step}][fields][{ opts.field_id }][style]" type="checkbox" value="list" checked={ (getState().config.guest[opts.step].fields[opts.field_id].style == 'list') }> 
                                <i class="fa fa-list"></i>
                            </label>
                            <input type="hidden" name="config[guest][{opts.step}][fields][{ opts.field_id }][style]" value={getState().config.guest[opts.step].fields[opts.field_id].style}/>
                        </div>
                    </div>

                    <div class="ve-field">
                        <label class="ve-label">{getLanguage().general.text_title}</label>
                        <div class="ve-input-group">
                            <span class="ve-input-group__addon">
                                <img src="{getLanguage().general.img}">
                            </span>
                            <input onchange="{parent.edit}" type="text" class="ve-input" name="language[{ opts.step }][{ opts.field.text }]" value={ getLanguage()[opts.step][opts.field.text] } />
                        </div>
                    </div>

                    <div class="ve-field">
                        <label class="ve-label">{getLanguage().general.text_placeholder}</label>
                        <div class="ve-input-group">
                            <span class="ve-input-group__addon">
                                <img src="{getLanguage().general.img}">
                            </span>
                            <input onchange="{parent.edit}" type="text" class="ve-input" name="language[{ opts.step }][{ opts.field.placeholder }]" value={ getLanguage()[opts.step][opts.field.placeholder] } />
                        </div>
                    </div>

                    <div class="ve-field">
                        <label class="ve-label">{getLanguage().general.text_tooltip}</label>
                        <div class="ve-input-group">
                            <span class="ve-input-group__addon">
                                <img src="{getLanguage().general.img}">
                            </span>
                            <input onchange="{parent.edit}" type="text" class="ve-input" name="language[{ opts.step }][{ opts.field.tooltip }]" value={ getLanguage()[opts.step][opts.field.tooltip] } />
                        </div>
                    </div>

                    <div class="ve-field">
                        <label class="ve-label">{getLanguage().general.text_default } { getLanguage().general.text_value}</label>
                        <input onchange="{parent.edit}" type="text" class="ve-input" name="config[{getAccount()}][{opts.step}][fields][{ opts.field_id }][value]" value={ opts.field.value } />
                    </div>

                    <div class="ve-field">
                        <label class="ve-label">Input format</label>
                        <input onchange="{parent.edit}" type="text" class="ve-input" name="config[{getAccount()}][{opts.step}][fields][{ opts.field_id }][format]" value={ opts.field.format } />
                    </div>
                </div>
            </div>

            <div class="qc-setting-tab-pane fade" role="tabpanel" id="{ opts.setting_id }_error" aria-labelledby="{ opts.setting_id }_error_tab">
                <div class="ve-editor__setting__content__section">
                    <div class="ve-field" >
                        <label class="ve-label">{getLanguage().general.text_error_rules}</label>
                        <qc_errors errors={parent.opts.field.errors} step={parent.opts.step} field_id={ parent.opts.field_id } edit="{parent.edit}"></qc_errors>
                    </div>
                </div>
            </div> 
            
            <div class="qc-setting-tab-pane fade" role="tabpanel" id="{ opts.setting_id }_advanced" aria-labelledby="{ opts.setting_id }_design_tab">
                <div class="ve-editor__setting__content__section">
                    <div class="ve-field" >
                        <label class="ve-label">{getLanguage().general.text_depends}</label>
                        <qc_depends depends={parent.opts.field.depends} step={parent.opts.step} field_id={ parent.opts.field_id } edit="{parent.edit}"></qc_depends>
                    </div>
                </div>
            </div> 

            <!-- <div class="qc-setting-tab-pane fade" role="tabpanel" id="{ opts.setting_id }_design" aria-labelledby="{ opts.setting_id }_design_tab">
                <design_setting></design_setting>
            </div>  -->
        </div>
    </qc_setting>
    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = opts.step +'_'+ opts.field_id +'_setting';

        var tag = this;

        edit(e){
            this.store.dispatch(this.opts.step+'/edit', $('#'+ tag.setting_id).find('form').serializeJSON());
        }

        editStyle(e){
            var data = $('#'+ tag.setting_id).find('form').serializeJSON();
            data.config.guest[tag.opts.step].fields[tag.opts.field_id].style = $(e.currentTarget).find('input').val();
            $(e.currentTarget).parent().find('input[type="hidden"]').val($(e.currentTarget).find('input').val());
            this.store.dispatch(this.opts.step+'/edit', data);
        }

        onDelete(){
            this.opts.ondelete();
        }
    </script>
</qc_datetime_setting>