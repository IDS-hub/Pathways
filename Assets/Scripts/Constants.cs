using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Constants
{
	public static bool IS_FEMALE = false;

    public static Color redColor = new Color(234f / 255f, 43f / 255f, 67f / 255f);
    public static Color orangeColor = new Color(239f / 255, 105f / 255f, 66f / 255f);
    public static Color yellowColor = new Color(247f / 255f, 196f / 255f, 69f / 255f);

	public static readonly string INVALID_SESSION_TITLE = "Oops!";
	public static readonly string INVALID_SESSION_DESC = "Invalid credentials. Please signin again!";

	public static readonly float CHARACTER_FADE_TIME = 1f;

	public static readonly int PURCHASE_SESSION_ID = 9;

	public static readonly string SHARE_LINK = "http://www.google.com";

}

//Values must be the same as in tStateMachine's array
public enum WindowPanels
{
	Loading,
    LoginSignUpMainWindow,
    SignUpWithEmail,
    LoginWithEmail,
    ForgotPassword,
    IntroductionWelcome,
    IntroductionPainGoAway,
	IntroductionPainGoAway2,
    IntroductionChronicPain,
	IntroductionChronicPain2,
    IntroductionAudio,
    AddDiagnoses,
    Creation_NEW,
    TutorialAddPain,
    TutorialZoomArea,
    TutorialTabArea,
    TutorialRateArea,
    PainSelector,
    Session,
	SessionList,
    Quiz,
    RatePainWindow,
    Statistic,
    ProfileWindow,
	Popup,
	Subscription,
	FeelGoodTask,
	PasswordSend,
	Home,
	FeedBack,
	Terms,
	Conditions
}

