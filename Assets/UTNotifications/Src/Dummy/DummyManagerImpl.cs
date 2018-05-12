#if UNITY_EDITOR || (!UNITY_IOS && !UNITY_ANDROID && !UNITY_WSA && !UNITY_METRO)

using UnityEngine;
using System.Collections;
using System.Collections.Generic;

namespace UTNotifications
{
    public class ManagerImpl : Manager
    {
    //public
        public override bool Initialize(bool willHandleReceivedNotifications, int startId, bool incrementalId)
        {
            NotSupported();
            return false;
        }

        public override void PostLocalNotification(string title, string text, int id, IDictionary<string, string> userData, string notificationProfile)
        {
            NotSupported();
        }

        public override void ScheduleNotification(int triggerInSeconds, string title, string text, int id, IDictionary<string, string> userData, string notificationProfile)
        {
            NotSupported();
        }

        public override void ScheduleNotificationRepeating(int firstTriggerInSeconds, int intervalSeconds, string title, string text, int id, IDictionary<string, string> userData, string notificationProfile)
        {
            NotSupported();
        }

        public override bool NotificationsEnabled()
        {
            NotSupported();
            return false;
        }

        public override void SetNotificationsEnabled(bool enabled)
        {
            NotSupported();
        }

        public override void CancelNotification(int id)
        {
            NotSupported();
        }

        public override void HideNotification(int id)
        {
            NotSupported();
        }

        public override void HideAllNotifications()
        {
            NotSupported();
        }

        public override void CancelAllNotifications()
        {
            NotSupported();
        }

        public override int GetBadge()
        {
            NotSupported();
            return 0;
        }

        public override void SetBadge(int bandgeNumber)
        {
            NotSupported();
        }
    }
}
#endif