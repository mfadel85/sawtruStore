import {StyleSheet} from 'react-native'

export default StyleSheet.create({
  container:{
    paddingLeft: 5,
    paddingRight: 6,
    width: 90,
    marginTop: 20
  },
  button:{
    width: 60,
    height: 60,
    borderRadius: 30,
    alignItems:'center',
    justifyContent:'center'
  },
  wrap:{
    alignItems:'center'
  },
  title:{
    marginTop: 6,
    fontSize: 11,
    // fontFamily: Constants.fontHeader,
    opacity: 0.9
  },

  iconView: {
    position: 'absolute',
    top: 0, 
    left: 0, 
    right: 0, 
    bottom: 0, 
    justifyContent: 'center', 
    alignItems: 'center'
  },
  icon:{
    width: 28,
    height: 28,
    resizeMode:'contain',
    tintColor: '#FFF', 
    marginBottom: 18
  },

  background: {
    backgroundColor: '#f1f1f1',

    width: 60,
    height: 60,
    borderRadius: 30,
    alignItems:'center',
    justifyContent:'center'
  }
})
