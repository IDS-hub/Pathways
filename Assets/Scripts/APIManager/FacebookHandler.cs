using UnityEngine;
using System.Collections;
using System.Collections.Generic;
using Facebook.MiniJSON;
using System;
using UnityEngine.UI;
using Facebook.Unity;

public class FacebookHandler : MonoBehaviour {

	// Use this for initialization
	public static FacebookHandler _instance = null;
	//	string meQueryString = "/v2.0/me?fields=id,first_name,friends.limit(100).fields(first_name,id,picture.width(128).height(128)),invitable_friends.limit(100).fields(first_name,id,picture.width(128).height(128))";

	//	private static List<object> friends = null;
	//	private static Dictionary<string, string> profile = null;
	//	private static List<object> scores = null;
	//	private static Dictionary<string, Texture> friendImages = new Dictionary<string, Texture>();

	//	private bool haveUserPicture = false;

	private static bool isInitialize = false;
	System.Action<string, bool> Callback;
	string userInfo;

	void Awake() {
		if (!isInitialize) {
			isInitialize = true;
			_instance = this;
			FB.Init(SetInit, OnHideUnity);
			DontDestroyOnLoad(gameObject);
		} else {
			Destroy(gameObject);
		}
	}

	public bool LOGIN {
		get {
			return FB.IsLoggedIn;
		}
	}

	private void SetInit() {
		enabled = true; 
		if (FB.IsLoggedIn) {
			Debug.Log("Logged In");
			OnLoggedIn();
		} else {
			Debug.Log("not logged in");
		}
	}

	private void OnHideUnity(bool isGameShown) {
	}

	void OnLoggedIn() {
	}

	// login to facebook using basic permissions
	public void DoLogIn(System.Action<String, bool> loginCallBack) {
		Callback = loginCallBack;

		if (!FB.IsLoggedIn)
			//FB.LogInWithReadPermissions(new List<string>() { "public_profile", "email", "user_friends, user_birthday, user_location"}, this.LoginCallback);
			FB.LogInWithReadPermissions(new List<string>() { "public_profile" }, this.LoginCallback);
		else {
			Debug.Log("aleady logged in");
			DealWithFBMenus(true);
		}
	}

	public void DoLogOut() {
		FB.LogOut();
	}

	void LoginCallback(IResult result) {
		Debug.Log("result :" + result.RawResult);
		//Debug.Log("access token " + AccessToken.CurrentAccessToken.UserId);
		if (FB.IsLoggedIn) {
			DealWithFBMenus(true);
		} else {
			if (Callback != null)
				Callback(result.RawResult, false);
		}
	}

	public string UserAccessToken {
		get { 
			return AccessToken.CurrentAccessToken.TokenString;
		}
	}

	void DealWithFBMenus(bool isLoggedIn) {
		if (isLoggedIn) {
			//FB.API ("/me?fields=name,email,location,birthday", HttpMethod.GET, DisplayUsername);
			FB.API("/me?fields=name", HttpMethod.GET, DisplayUsername);
		} 
	}

	// geting user's fb details
	void DisplayUsername(IResult result) {
		if (result.Error == null) {
			userInfo = result.RawResult;
			Debug.Log("response with fb data: " + result.RawResult);
			if (Callback != null)
				Callback(result.RawResult, true);
		} else {
			Debug.Log(result.Error);
			if (Callback != null)
				Callback(result.Error, false);
		}

		Callback = null;

	}

	public string UserInfo {
		get { 

			MiniJsonObject userData = new MiniJsonObject(userInfo);

			string name = userData.GetField("name", "N/A");
			string dob = userData.GetField("birthday", "N/A");
			string location = userData.GetJsonObject("location").GetField("name", "N/A");
			string email = userData.GetField("email", "N/A").Replace("&#x0040;", "@");
			string profileLink = string.Format("http://graph.facebook.com/{0}/picture?type=large", userData.GetField("id", ""));
//			string fbID = userData.GetField("id", "");

			MiniJsonObject data = new MiniJsonObject();
			data.AddField("name", name);
			data.AddField("location", location);
			data.AddField("dob", dob);
			data.AddField("email", email);
			data.AddField("profile_pic", profileLink);

			return data.ToString();
		}
	}

	public void FeedShare(Uri link, string linkName = "", string title = "", string description = "", Uri pictureLink = null) {
		//FB.FeedShare("", link, linkName, title, description, null, pictureLink);

		//FB.ShareLink(
		FB.ShareLink(
			link,
			title,
			description,
			pictureLink,
			result => {
				if (result.Error != null) {
					Debug.Log(result.Error);
					return;
				}
				//Debug.Log("FeedShare " + result.RawResult);
			});
	}

}
