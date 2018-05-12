using UnityEngine;
using System;
using System.Collections;
using System.Collections.Generic;

namespace UTNotifications
{
    /// <summary>
    /// The main class providing UTNotifications API.
    /// </summary>
    /// <remarks>
    /// It's a singleton which automatically creates a single <c>GameObject</c>
    /// "UTNotificationsManager" which is not destroyed on a scene loading.
    ///
    /// Usage:
    /// 1. If you would like to use not only local/scheduled notifications
    ///    but also push notifications please subscribe to
    ///    <c>OnSendRegistrationId</c> event. <seealso cref="Manager.OnSendRegistrationId"/>
    /// 2. Call <c>UTNotifications.Manager.Instance.Initialize</c> in order
    ///    to initialize the notifications system. <seealso cref="Manager.Initialize"/>
    /// 3. Now you can start using all the <c>UTNotifications.Manager</c> API
    ///    methods (see the methods description for further information)
    /// </remarks>
    public abstract class Manager : MonoBehaviour
    {
    //public
        /// <summary>
        /// This is how you access the only instance of the Manager.
        /// </summary>
        public static Manager Instance
        {
            get
            {
                InstanceRequired();
                return m_instance;
            }
        }

        /// <summary>
        /// Initialize the Manager. Can be called more than once.
        /// </summary>
        /// <param name="willHandleReceivedNotifications">Set to <c>true</c> to be able to handle received notifications. <seealso cref="OnNotificationsReceived"/></param>
        /// <param name="startId">ID of a first received push notification</param>
        /// <param name="incrementalId">If set to <c>true</c> each received push notfication will have an ID = previous push notification ID + 1. Otherwise all received push notifications will have ID = <c>startId</c></param>
        /// <returns><c>true</c>, if initialized successfully, <c>false</c> otherwise.</returns>
        /// <remarks>
        /// Please set <c>willHandleReceivedNotifications</c> to <c>true</c> only if you will handle received notifications using <c>OnNotificationsReceived</c>. Otherwise, all the received notifications will be stored and never cleaned.
        /// If <c>incrementalId</c> is <c>false</c> (default value), then new push notifications will replace old ones on Android so only one push notification can be shown at a time.
        /// </remarks>
        public abstract bool Initialize(bool willHandleReceivedNotifications, int startId = 0, bool incrementalId = false);

        /// <summary>
        /// Posts the local notification at once.
        /// </summary>
        /// <param name="title">The notification title.</param>
        /// <param name="text">The notification text.</param>
        /// <param name="id">The notification ID (any notifications with the same id will be replaced by this one). <seealso cref="CancelNotification"/> <seealso cref="HideNotification"/></param>
        /// <param name="userData">(Optional) A custom IDictionary<string, string> that can be received in <c>OnNotificationsReceived<c/>. <seealso cref="OnNotificationsReceived"/></param>
        /// <param name="notificationProfile">(Optional) The name of the notification profile (sound & icons settings).</param>
        public abstract void PostLocalNotification(string title, string text, int id, IDictionary<string, string> userData = null, string notificationProfile = null);

        /// <summary>
        /// Schedules the local notification.
        /// </summary>
        /// <param name="triggerInSeconds">Seconds value from now when the notification will be shown.</param>
        /// <param name="title">The notification title.</param>
        /// <param name="text">The notification text.</param>
        /// <param name="id">The notification ID (any notifications with the same id will be replaced by this one). <seealso cref="CancelNotification"/> <seealso cref="HideNotification"/></param>
        /// <param name="userData">(Optional) A custom IDictionary<string, string> that can be received in <c>OnNotificationsReceived<c/>. <seealso cref="OnNotificationsReceived"/></param>
        /// <param name="notificationProfile">(Optional) The name of the notification profile (sound & icons settings).</param>
        public abstract void ScheduleNotification(int triggerInSeconds, string title, string text, int id, IDictionary<string, string> userData = null, string notificationProfile = null);

        /// <summary>
        /// Schedules the local notification.
        /// </summary>
        /// <param name="triggerDateTime">DateTime value when the notification will be shown.</param>
        /// <param name="title">The notification title.</param>
        /// <param name="text">The notification text.</param>
        /// <param name="id">The notification ID (any notifications with the same id will be replaced by this one). <seealso cref="CancelNotification"/> <seealso cref="HideNotification"/></param>
        /// <param name="userData">(Optional) A custom IDictionary<string, string> that can be received in <c>OnNotificationsReceived<c/>. <seealso cref="OnNotificationsReceived"/></param>
        /// <param name="notificationProfile">(Optional) The name of the notification profile (sound & icons settings).</param>
        public void ScheduleNotification(DateTime triggerDateTime, string title, string text, int id, IDictionary<string, string> userData = null, string notificationProfile = null)
        {
            ScheduleNotification(TimeUtils.ToSecondsFromNow(triggerDateTime), title, text, id, userData, notificationProfile);
        }

        /// <summary>
        /// Schedules the notification repeating.
        /// </summary>
        /// <param name="firstTriggerInSeconds">Seconds value from now when the notification will be shown first time.</param>
        /// <param name="intervalSeconds">Seconds between the notification shows (see remarks).</param>
        /// <param name="title">The notification title.</param>
        /// <param name="text">The notification text.</param>
        /// <param name="id">The notification ID (any notifications with the same id will be replaced by this one). <seealso cref="CancelNotification"/> <seealso cref="HideNotification"/></param>
        /// <param name="userData">(Optional) A custom IDictionary<string, string> that can be received in <c>OnNotificationsReceived<c/>. <seealso cref="OnNotificationsReceived"/></param>
        /// <param name="notificationProfile">(Optional) The name of the notification profile (sound & icons settings).</param>
        /// <remarks> 
        /// Please note that the actual interval may be different.
        /// On iOS there are only fixed options like every minute, every day, every week and so on. So the provided <c>intervalSeconds</c> value will be approximated by one of the available options.
        /// </remarks>
        public abstract void ScheduleNotificationRepeating(int firstTriggerInSeconds, int intervalSeconds, string title, string text, int id, IDictionary<string, string> userData = null, string notificationProfile = null);

        /// <summary>
        /// Schedules the notification repeating.
        /// </summary>
        /// <param name="firstTriggerDateTime">DateTime value when the notification will be shown first time.</param>
        /// <param name="intervalSeconds">Seconds between the notification shows (see remarks).</param>
        /// <param name="title">The notification title.</param>
        /// <param name="text">The notification text.</param>
        /// <param name="id">The notification ID (any notifications with the same id will be replaced by this one). <seealso cref="CancelNotification"/> <seealso cref="HideNotification"/></param>
        /// <param name="userData">(Optional) A custom IDictionary<string, string> that can be received in <c>OnNotificationsReceived<c/>. <seealso cref="OnNotificationsReceived"/></param>
        /// <param name="notificationProfile">(Optional) The name of the notification profile (sound & icons settings).</param>
        /// <remarks> 
        /// Please note that the actual interval may be different.
        /// On iOS there are only fixed options like every minute, every day, every week and so on. So the provided <c>intervalSeconds</c> value will be approximated by one of the available options.
        /// </remarks>
        public void ScheduleNotificationRepeating(DateTime firstTriggerDateTime, int intervalSeconds, string title, string text, int id, IDictionary<string, string> userData = null, string notificationProfile = null)
        {
            ScheduleNotificationRepeating(TimeUtils.ToSecondsFromNow(firstTriggerDateTime), intervalSeconds, title, text, id, userData, notificationProfile);
        }

        /// <summary>
        /// Checks if the notifications are enabled.
        /// </summary>
        public abstract bool NotificationsEnabled();

        /// <summary>
        /// Enables or disables the notifications.
        /// </summary>
        /// <remarks> 
        /// Please note that enabling may change the registrationId so <c>OnSendRegistrationId</c> event will be called. <seealso cref="OnSendRegistrationId"/>
        /// </remarks>
        public abstract void SetNotificationsEnabled(bool enabled);

        /// <summary>
        /// Cancels the notification with the specified id (ignored if the specified notification is not found).
        /// </summary>
        /// <remarks> 
        /// If the specified notification is scheduled or repeating all the future shows will be also canceled.
        /// </remarks>
        public abstract void CancelNotification(int id);

        /// <summary>
        /// Hides the notification with the specified id (ignored if the specified notification is not found).
        /// </summary>
        /// <remarks>
        /// If the specified notification is scheduled or repeating all the future shows will remain scheduled. Not supported and will be ignored on Windows Store.
        /// </remarks>
        public abstract void HideNotification(int id);

        /// <summary>
        /// Cancels all the notifications. <seealso cref="CancelNotification"/>
        /// </summary>
        public abstract void CancelAllNotifications();

        /// <summary>
        /// Hides all the notifications. <seealso cref="HideNotification"/>
        /// </summary>
        /// <remarks>
        /// Not supported and will be ignored on Windows Store.
        /// </remarks>
        public abstract void HideAllNotifications();

        /// <summary>
        /// Returns the app icon badge value (iOS only).
        /// </summary>
        /// <remarks>
        /// Will always return <c>0</c> on non-iOS platforms.
        /// </remarks>
        public abstract int GetBadge();

        /// <summary>
        /// Sets the app icon badge value (iOS only).
        /// </summary>
        /// <remarks>
        /// Will be ignored on non-iOS platforms.
        /// </remarks>
        public abstract void SetBadge(int bandgeNumber);

        public delegate void OnSendRegistrationIdHandler(string providerName, string registrationId);
        /// <summary>
        /// Occurs when the registrationId for push notifications is received and should be sent to your server.
        /// </summary>
        /// <param name="providerName">A name of a push notifications provider. May be <c>"iOS"</c>, <c>"GooglePlay"</c> or <c>"Amazon"</c></param>
        /// <param name="registrationId">The received registrationId (encoded on iOS - see remarks)</param>
        /// <remarks>
        /// Please note, that <c>registrationId</c> value on iOS is encoded value of APNS registration token (which is originally received as a binary buffer).
        /// The encoding can be <c>Base64</c> (by default) or <c>HEX</c> based on the UTNotifications settings:
        /// <c>Edit -> Project Settings -> UTNotifications -> iOS Settings -> APNS Registration Id encoding</c>.
        /// On Android <c>registrationId</c> is not encoded.
        /// </remarks>
        public event OnSendRegistrationIdHandler OnSendRegistrationId;

        public delegate void OnNotificationClickedHandler(ReceivedNotification notification);
        /// <summary>
        /// Occurs when user tapped/clicked on a notification.
        /// </summary>
        /// <param name="notification">Clicked notification. <seealso cref="UTNotifications.ReceivedNotification"/>
        /// <remarks>
        /// To be able to handle a click on a notification which started an app, please subscribe on the event before calling <c>Initialize</c>.
        /// </remarks>
        public event OnNotificationClickedHandler OnNotificationClicked;

        public delegate void OnNotificationsReceivedHandler(IList<ReceivedNotification> receivedNotifications);
        /// <summary>
        /// Occurs when one or more local, scheduled or push notifications are received.
        /// </summary>
        /// <param name="receivedNotifications">A list of received <c>ReceivedNotification</c>s. <seealso cref="UTNotifications.ReceivedNotification"/>
        /// <remarks>
        /// iOS doesn't provide a list of all notifications shown when an app wasn't running in foreground. This list will contain only a notification, which was clicked and all notifications shown when an app is running in foreground.
        /// On other platforms, you'll receive a list of all shown notifications.
        /// </remarks>
        public event OnNotificationsReceivedHandler OnNotificationsReceived;

    //protected
        protected bool OnSendRegistrationIdHasSubscribers()
        {
            return OnSendRegistrationId != null;
        }

        protected void _OnSendRegistrationId(string providerName, string registrationId)
        {
            OnSendRegistrationId(providerName, registrationId);
        }

        protected bool OnNotificationClickedHasSubscribers()
        {
            return OnNotificationClicked != null;
        }
        
        protected void _OnNotificationClicked(ReceivedNotification notification)
        {
            OnNotificationClicked(notification);
        }

        protected bool OnNotificationsReceivedHasSubscribers()
        {
            return OnNotificationsReceived != null;
        }

        protected void _OnNotificationsReceived(IList<ReceivedNotification> receivedNotifications)
        {
            OnNotificationsReceived(receivedNotifications);
        }

        protected virtual void OnDestroy()
        {
            m_instance = null;
        }

        protected void NotSupported(string feature = null)
        {
            if (feature == null)
            {
                Debug.LogWarning("UTNotifications: not supported on this platform");
            }
            else
            {
                Debug.LogWarning("UTNotifications: " + feature + " feature is not supported on this platform");
            }
        }

    //private
        private static void InstanceRequired()
        {
            if (!m_instance)
            {
                GameObject gameObject = new GameObject("UTNotificationsManager");
                m_instance = gameObject.AddComponent<ManagerImpl>();
                DontDestroyOnLoad(gameObject);
            }
        }

        private static Manager m_instance = null;
    }
}