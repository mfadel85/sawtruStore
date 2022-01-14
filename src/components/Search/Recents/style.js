import {StyleSheet, Platform} from 'react-native'
import {Color} from '@common'

export default StyleSheet.create({
  container:{
    flexDirection: 'row',
    marginHorizontal: 20,
    marginTop: 15,
    marginBottom: 10,
    alignItems:'center',
    justifyContent:'space-between'
  },
  recents:{
    flexDirection:'row',
    alignItems:'center'
  },
  text:{
    fontSize: 16,
    marginRight: 5
  }
})
