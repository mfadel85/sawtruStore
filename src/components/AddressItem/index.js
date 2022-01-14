import React from 'react'
import {
  View,
  Image,
  TextInput,
  TouchableOpacity,
  Animated,
  Text
} from 'react-native'
import styles from './style'
import {Images, withTheme} from '@common'

class Item extends React.Component {

  render() {
    let {item, onPress, selected, onRemove} = this.props

    var address = ""
    if (item.address_1 != "") {
      address += item.address_1 + ", "
    }

    if (item.city != "") {
      address += item.city + ", "
    }

    if (item.state != "") {
      address += item.state + ", "
    }

    if (item.country != "") {
      address += item.country
    }

    const {
      theme:{
        colors:{
          background, text
        }
      }
    } = this.props
    return (
      <TouchableOpacity style={[styles.container, {backgroundColor: background}]} activeOpacity={0.85} onPress={onPress}>
        <View style={styles.content}>
          <Text style={[styles.name, {color: text}]}>{item.first_name+" "+item.last_name}</Text>
          <Text style={[styles.text, {color: text}]}>{item.email}</Text>
          <Text style={[styles.text, {color: text}]}>{item.phone}</Text>
          <Text style={[styles.text, {color: text}]}>{item.postcode}</Text>
          <Text style={[styles.text, {color: text}]}>{address}</Text>
        </View>
        <View style={styles.buttons}>
          {selected && <Image source={Images.IconCheck} style={[styles.icon, {tintColor: "green"}]}/>}
          {!selected && <View />}
          {!selected && (
            <TouchableOpacity onPress={onRemove}>
              <Image source={require("@images/ic_trash.png")} style={styles.icon}/>
            </TouchableOpacity>
          )}
        </View>
      </TouchableOpacity>
    )
  }

}

export default withTheme(Item)
