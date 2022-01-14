import {StyleSheet, Platform} from 'react-native'
import {Color} from '@common'

export default StyleSheet.create({
  container:{
    flexDirection: 'row',
    marginHorizontal: 15,
    marginVertical: 10,
    flexWrap:'wrap'
  },
  button:{
    flexDirection:'row',
    alignItems:'center',
    marginHorizontal: 20,
    marginTop: 15,
    marginBottom: 10,
  },
  text:{
    fontSize: 16,
    marginRight: 5
  }
})
